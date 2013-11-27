/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#include <QObject>
#include <QUrl>
#include <QNetworkRequest>
#include <QNetworkReply>
#include <QNetworkAccessManager>
#include <QEventLoop>
#include <QBuffer>
#include <QCoreApplication>
#include <QDateTime>
#include <QStringList>
#include "webapiclient.h"





// Structure to specify query options.
webapi_query_options_t::webapi_query_options_t()
{
	range_start=0;
	range_end=0;
	tv_setup=NULL;
}

webapi_query_options_t::~webapi_query_options_t()
{
}


WebApiClient::WebApiClient(webapi_error_t (*webapi_object_factory)(webapi_object_t** p_obj, QString response_str, QString request_xml, webapi_object_type_t type ))
{
	this->xml_package_response = webapi_object_factory;
}

#ifdef HTTP_METRICS

QString http_metrics_filename = "http_metrics.txt";

#endif

WebApiClient::~WebApiClient()
{
#ifdef HTTP_METRICS
    // If HTTP_METRICS is defined, print the metrics when closing the WebApiClient object.
	int   total = 0;
	FILE* metric_file = fopen(http_metrics_filename.toUtf8().data(), "a");

	if (metric_file)
	{
		QList<QString> keys = metrics.keys();
		QList<QString>::iterator i; 

		fprintf(metric_file, "\n%s =============================================\n", QDateTime::currentDateTime().toString().toUtf8().data());
		for (i = keys.begin(); i != keys.end(); i++)
		{
			fprintf(metric_file, "%s\t%d\n", i->toUtf8().data(), metrics[*i]);
			total += metrics[*i];
		}

		fprintf(metric_file, "Total\t%d\n", total);

		fclose(metric_file);
	}
#endif

}

// Set the language to use for queries and responses.
void WebApiClient::set_preferred_language(QString lang)
{
	preferred_language = lang;	
}

// Set the country to use for queries and responses.
void WebApiClient::set_country(QString in_country)
{
	country = in_country;	
}
// If true, saves request XML in the WebApiClient object.
void WebApiClient::set_save_requests(bool value)
{
	this->save_requests = value;
}

// A simple helper method to find a child of a QDom node.
static QDomNode find_dom_child(QDomNode node, QString childname)
{
	int i;
	QDomNodeList children = node.childNodes();
	
	for (i=0; i< children.count();i++)
	{
		if (children.at(i).nodeName() == childname)
		{
			
			return children.at(i);
		}
	}

	return QDomNode();
}

webapi_error_t WebApiClient::initialize(QString in_service_url, QString in_client_id, QString in_user_id)
{
	webapi_error_t error = WEBAPI_OK;

	if ((in_service_url.isEmpty() ) || (in_client_id.isEmpty()))
	{
		return WEBAPI_ERROR;
	}
	
    this->save_requests = false; // By default, do not save request xml in the WebApiClient object.

	this->client_id   = in_client_id;
	this->user_id     = in_user_id;
	this->service_url = in_service_url;

#ifdef DEBUGPRINTFS
	fprintf( stderr, "%s %s %s\n", in_client_id.toUtf8().data(), in_user_id.toUtf8().data(), in_service_url.toUtf8().data());
#endif

	//If we don't have a user ID, we have to register
	if (this->user_id.isEmpty() )
	{
		
        // Request document.
		QDomDocument query_doc;
		QDomElement queries_element;
		QDomElement reg_query;
		QDomElement client_element;


        // Response document.
		QDomDocument    doc;
		QDomElement     docElem;
		QDomNode		child;

		
		QString status;
		webapi_object_t* wobj = NULL;

		xml_create_queries_element( &query_doc, &queries_element);
		xml_add_query( &query_doc, &queries_element, &reg_query, WEBAPI_XML_REGISTER);

		
		client_element = query_doc.createElement(WEBAPI_XML_CLIENT);
		client_element.appendChild(query_doc.createTextNode(client_id));

		reg_query.appendChild(client_element);

		QString request_str = query_doc.toString();
		QString result = perform_http_post(service_url, request_str);
		

        // Parse the result to find the response user_id.
		doc.setContent(result);

		docElem = doc.documentElement();
		if (docElem.nodeName() == WEBAPI_XML_RESPONSES)
		{
			child = find_dom_child( docElem, WEBAPI_XML_RESPONSE);
			if (!child.isNull())
			{
				if (child.attributes().namedItem(WEBAPI_XML_STATUS).nodeValue() == WEBAPI_XML_OK)  //check that its OK to not have a 'status'
				{
					child  = find_dom_child( child, WEBAPI_XML_USER);
					if (!child.isNull())
					{
						user_id = child.toElement().text();
					}	
				}
			}
		}

		if (user_id.isEmpty())
		{
			return WEBAPI_ERROR;  //Registration was not successful if we didn't get a user id
		}
	}
	return error;
}


QString WebApiClient::get_user_id()
{
	return user_id;
}


// Set up a proxy

void WebApiClient::set_proxy(QString proxy_url, int proxy_port, QString proxy_user, QString proxy_passwd)
{
	if (proxy_url.isEmpty())
	{
		return;
	}

	http_proxy = QNetworkProxy(QNetworkProxy::HttpProxy, proxy_url, proxy_port);
	
	if (!proxy_user.isEmpty())
	{
		http_proxy.setUser(proxy_user);
	}
	if (!proxy_passwd.isEmpty())
	{
		http_proxy.setPassword (proxy_passwd);
	}

	QNetworkProxy::setApplicationProxy( http_proxy);

}

// Set up network access.

#ifdef HTTP_METRICS
QByteArray WebApiClient::perform_http_get_metered(QString tag, QString url, int *return_code )
#else
QByteArray WebApiClient::perform_http_get(QString url,  int *return_code )
#endif
{
	QNetworkRequest net_request;
	QNetworkAccessManager net_manager(NULL);
	QNetworkReply*  net_reply = NULL;
	QEventLoop local_event_loop;

#if 0
	connect(net_manager, 
		SIGNAL(finished(QNetworkReply*)), 
		this, 
		SLOT(process_net_reply(QNetworkReply*)));
#endif
	
	
    // Connect the network manager's finished signal to kill the event loop.
	net_manager.connect(&net_manager, SIGNAL(finished(QNetworkReply*)), &local_event_loop, SLOT(quit()));

    // url = url.replace("%2F","/");
	net_request = QNetworkRequest(QUrl(url));
	
	net_reply = net_manager.get(net_request);

#if 1
	net_reply->ignoreSslErrors();
#endif

	
#ifdef DEBUGPRINTFS
	printf(" waiting...\n");
#endif

	// Execute the event loop, excluding mouse and keyboard events.
	local_event_loop.exec(QEventLoop::ExcludeUserInputEvents);

#ifdef DEBUGPRINTFS
	printf(" done\n");
#endif
    // Get all the data.

	if (return_code != NULL)
	{
		*return_code = net_reply->attribute(QNetworkRequest::HttpStatusCodeAttribute).toInt();
	}

	QByteArray qba = net_reply->readAll();

#ifdef LOG_REQUESTS
	FILE* f = fopen("webapi_http_get.dat", "wb");
	if (f)
	{
		fwrite(qba.data(), qba.count(), 1, f);
		
		fclose(f);
	}
#endif

#ifdef HTTP_METRICS
	
	
	metric_mutex.lock();
	

	int val = metrics.value(tag);
	val += qba.count();
	metrics.insert(tag, val);


	metric_mutex.unlock();
#endif


	
#ifdef DEBUGPRINTFS
	printf("got %d bytes\n", qba.length()   );
#endif	
		
	return qba;
}

#ifdef HTTP_METRICS
QString WebApiClient::perform_http_post_metered(QString tag, QString url, QString post_data)
#else
QString WebApiClient::perform_http_post(QString url, QString post_data)
#endif

{
	QNetworkRequest net_request;
	QNetworkAccessManager net_manager(NULL);
	QNetworkReply*  net_reply = NULL;
	QEventLoop local_event_loop;
	
#ifdef LOG_REQUESTS
	FILE* f = fopen("webapi_request.xml", "wb");
	if (f)
	{

		fprintf(f, "%s\n", post_data.toUtf8().data());
		fclose(f);

	}
#endif

    // Connect the network manager's finished signal to kill the event loop.
	net_manager.connect(&net_manager, SIGNAL(finished(QNetworkReply*)), &local_event_loop, SLOT(quit()));

	net_request = QNetworkRequest(QUrl(url));

#ifdef DEBUGPRINTFS
	fprintf(stderr," About to do http post %s\n", url.toUtf8().data());	
#endif

	net_reply = net_manager.post(net_request,   post_data.toUtf8() );

#if 1
	net_reply->ignoreSslErrors();
#endif
	

    // Execute the event loop, excluding mouse and keyboard events.
    local_event_loop.exec(QEventLoop::ExcludeUserInputEvents);


    // Get all the data.

#ifdef DEBUGPRINTFS
	fprintf(stderr," finished http\n");	
#endif

	QByteArray ba = net_reply->readAll();
	QString s = QString::fromUtf8( ba.data(), ba.length());
		
	

#ifdef LOG_REQUESTS
	f = fopen("webapi_response.xml", "wb");
	if (f)
	{
		fprintf(f, "%s\n", s.toUtf8().data());
		fclose(f);
	}
#endif

#ifdef HTTP_METRICS
	
	
	metric_mutex.lock();
	

	int val = metrics.value(tag);
	val += ba.count();
	metrics.insert(tag, val);


	metric_mutex.unlock();
#endif

	return s;
}




// TOC Lookup Query
webapi_error_t WebApiClient::toc_lookup(
	webapi_object_type_t fetch_type,
	QString toc,
	webapi_query_options_t *options,
	webapi_object_t **obj
	)
{
    // Currently, processing options is not implemented.
	QDomDocument query_doc;
	QDomElement  queries_element;
	QDomElement  fetch_query;

	if (obj == NULL)
	{
		return WEBAPI_ERROR;
	}

    // Create a QUERIES xml document with auth structure
	xml_create_queries_element(&query_doc, &queries_element);

    // Add a  *_FETCH query.
	xml_add_toc_lookup(&query_doc, &queries_element, &fetch_query, fetch_type, toc);


	
	if (options)
	{

		if (!options->mode.isEmpty())
		{
			QDomElement mode = query_doc.createElement(WEBAPI_XML_MODE);
			mode.appendChild(query_doc.createTextNode(options->mode));
			fetch_query.appendChild(mode);
		}

		
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &fetch_query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}

        // Put other options here.
	}

    // Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

    // Package up the response.
	return xml_package_response(obj, result, (this->save_requests ? request_str : "" ), fetch_type);
}

// Fetch Query
webapi_error_t WebApiClient::fetch(webapi_object_type_t fetch_type, QString gnid, webapi_query_options_t* options, webapi_object_t** obj)
{
    // Currently, processing options is not implemented.

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement fetch_query;
	QString result;
	webapi_error_t error = WEBAPI_OK;

	if (obj == NULL)
	{
		return WEBAPI_ERROR;
	}

    // Create a QUERIES xml document with auth structure
	xml_create_queries_element( &query_doc, &queries_element);

    // Add a  *_FETCH query.
	error = xml_add_fetch(&query_doc, &queries_element, &fetch_query,  fetch_type,   gnid );

	if (error != WEBAPI_OK)  //error creating fetch query
	{
		return error;
	}

	if (options)
	{
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &fetch_query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}

		if ( (fetch_type == webapi_contributor) && (!options->expand_series.isEmpty()))
		{
			xml_add_query_option(&query_doc, &fetch_query, WEBAPI_XML_EXPAND_SERIES, options->expand_series);
		}
		
#if 0
		if ((options->tv_setup)&&((fetch_type == webapi_work )||(fetch_type == webapi_contributor)))
		{
            // AV_WORK and CONTRIBUTOR fetches can specify TV channels.
			xml_add_channel_ids_to_query(&query_doc, &fetch_query, options->tv_setup);
		}
#endif
		
        // Put other options here.
	}
	
	QString request_str = query_doc.toString();

	
	result = perform_http_post(service_url, request_str);

    // Package up the response.
	return xml_package_response(obj, result,(this->save_requests ? request_str : "" ), fetch_type);
}

// Search Query
webapi_error_t WebApiClient::search(webapi_object_type_t fetch_type, QString field, QString value, webapi_query_options_t* options, webapi_object_t** obj)
{
    // Currently, processing options is not implemented.

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement fetch_query;
	webapi_error_t error = WEBAPI_OK;

	if (obj == NULL)
	{
		return WEBAPI_ERROR;
	}

    // Create a QUERIES xml document with auth structure.
	xml_create_queries_element( &query_doc, &queries_element);

    // Add a  *_SEARCH query.

	error = xml_add_search(&query_doc, &queries_element, &fetch_query,  fetch_type,   field, value );

	if (error != WEBAPI_OK)  //error creating search query
	{
		return error;
	}

	if (options)
	{
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &fetch_query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}

		if (!options->mode.isEmpty())
		{
			xml_add_mode(&query_doc, &fetch_query, options->mode);
		}
		
        // Add START if user sets something valid.
		if (options->range_start > 0)
		{
			QDomElement xml_range_start;
			QDomElement xml_range_end;
			QDomElement xml_range;
			
			xml_range = query_doc.createElement(WEBAPI_XML_RANGE);
			
			xml_range_start = query_doc.createElement(WEBAPI_XML_START);
			xml_range_start.appendChild(query_doc.createTextNode( QString::number(options->range_start)));
			xml_range.appendChild(xml_range_start);
			
			xml_range_end = query_doc.createElement(WEBAPI_XML_END);
			xml_range_end.appendChild(query_doc.createTextNode( QString::number(options->range_end)));
			xml_range.appendChild(xml_range_end);

			fetch_query.appendChild(xml_range);
			


		}

        // Put other options here.
	}
	
    // Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

    // Package up the response.
	return xml_package_response(obj, result, (this->save_requests ? request_str : "" ), fetch_type);
}


// TV Providers Query

webapi_error_t WebApiClient::tv_get_providers(webapi_tv_setup_t*  setup, webapi_query_options_t* options, webapi_object_t** obj)
{
    // Currently, processing options is not implemented.

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;

	if ((obj == NULL) || (setup == NULL))
	{
		return WEBAPI_ERROR;
	}


    // Create a QUERIES XML document with AUTH structure.
	xml_create_queries_element( &query_doc, &queries_element);


	xml_add_query(&query_doc, &queries_element, &query, WEBAPI_XML_TVPROVIDER_LOOKUP);

	QDomElement postalcode = query_doc.createElement(WEBAPI_XML_POSTALCODE);
	postalcode.appendChild(query_doc.createTextNode(setup->postalcode));
	query.appendChild(postalcode);
	
	
	if (options)
	{
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}
				

        // Put other options here.
	}
	
    // Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

    // Package up response.
	return xml_package_response(obj, result, (this->save_requests ? request_str : "" ), webapi_tvchannels);
}



// Get TV Channels
webapi_error_t WebApiClient::tv_get_channels(webapi_tv_setup_t*  setup, webapi_query_options_t* options, webapi_object_t** obj)
{
    // Currently, processing options is not implemented.

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;

	// Return an error if the TV Provider was not set up.
	if ((obj == NULL) || (setup == NULL))
	{
		return WEBAPI_ERROR;
	}

    // Create a QUERIES xml document with auth structure.
	xml_create_queries_element( &query_doc, &queries_element);

	
	xml_add_query(&query_doc, &queries_element, &query, WEBAPI_XML_TVCHANNEL_LOOKUP);


	
	if (!setup->enabled_provider.isEmpty())
	{
		
    // Add TV Provider


		xml_add_mode(&query_doc, &query, WEBAPI_XML_TVPROVIDER);

		QDomElement provider = query_doc.createElement(WEBAPI_XML_GN_ID);
		provider.appendChild(query_doc.createTextNode(setup->enabled_provider));
		query.appendChild(provider);
	}
	else if ( setup->dvbs.count() > 0)
	{  
        // European setup sends up dvb triplets
		
		int i;
		for (i=0;i<setup->dvbs.count();i++)
		{
			QString dvb_temp = setup->dvbs[i];
			dvb_temp.replace("://",".");
			QStringList dvb_triplet = dvb_temp.split('.');

			if (dvb_triplet.count() != 4)
			{
				return WEBAPI_ERROR;  //invalid triplet
			}

			if (dvb_triplet[0].toUpper() != "DVB")
			{
				return WEBAPI_ERROR;  //triplet needs to start with DVB
			}


			QDomElement dvb_ids_xml = query_doc.createElement(WEBAPI_XML_DVBIDS);
			QDomElement onid_xml = query_doc.createElement(WEBAPI_XML_ONID);
			QDomElement tsid_xml = query_doc.createElement(WEBAPI_XML_TSID);
			QDomElement sid_xml = query_doc.createElement(WEBAPI_XML_SID);

			onid_xml.appendChild(query_doc.createTextNode(dvb_triplet[1]));
			tsid_xml.appendChild(query_doc.createTextNode(dvb_triplet[2]));
			sid_xml.appendChild(query_doc.createTextNode(dvb_triplet[3]));

			dvb_ids_xml.appendChild(onid_xml);
			dvb_ids_xml.appendChild(tsid_xml);
			dvb_ids_xml.appendChild(sid_xml);

			query.appendChild(dvb_ids_xml);
		}

		xml_add_mode(&query_doc, &query, "DVBIDS");

	}
	else
	{
        // User did not specify a provider, so return an error.
		return WEBAPI_ERROR;
	}
	


	if (options)
	{
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}
				

        // Put other options here.
	}
	
    // Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

    // Package up the response.
	return xml_package_response(obj, result,(this->save_requests ? request_str : "" ), webapi_tvproviders);
}



// Get TV program batch list.
webapi_error_t WebApiClient::tv_get_program_batch_list(webapi_tv_setup_t*  setup, webapi_query_options_t* options, webapi_object_t** obj)
{
	// Currently processing options is not implemented

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;

	int i;

	if ((obj == NULL) || (setup == NULL))
	{
		return WEBAPI_ERROR;
	}

	// Create QUERIES XML document with AUTH structure
	xml_create_queries_element( &query_doc, &queries_element);

	
	xml_add_query(&query_doc, &queries_element, &query, WEBAPI_XML_TVGRIDBATCH_UPDATE);

	if (setup->enabled_channel_ids.count() == 0)
	{
		return WEBAPI_ERROR;  //no channels enabled!
	}

	if ( (setup->enabled_channel_id_stamps.count() > 0) &&( setup->enabled_channel_id_stamps.count() != setup->enabled_channel_ids.count()))
	{
		return WEBAPI_ERROR;  
		//user specified channel stamps, but not enough stamps to cover all specifed channels
		//user should specify no stamps, or specify 1 stamp per channel (empty-string for a stamp is OK)
	}

	for (i=0;i<setup->enabled_channel_ids.count();i++)
	{
		QDomElement gn_id = query_doc.createElement(WEBAPI_XML_GN_ID);
		gn_id.appendChild( query_doc.createTextNode(setup->enabled_channel_ids[i]));

		

		QDomElement state_type = query_doc.createElement(WEBAPI_XML_STATE_TYPE);

		if (setup->enabled_channel_ids[i].contains("_ALL"))
		{
			state_type.appendChild( query_doc.createTextNode(setup->enabled_channel_ids[i]));
		}
		else
		{
			state_type.appendChild( query_doc.createTextNode(WEBAPI_XML_TVCHANNEL));
		}

		QDomElement state_info = query_doc.createElement(WEBAPI_XML_STATE_INFO);
		
		if (!setup->enabled_channel_ids[i].contains("_ALL"))
		{
			state_info.appendChild(gn_id);
		}

		state_info.appendChild(state_type);

		if ( (i <setup->enabled_channel_id_stamps.count()) && (! setup->enabled_channel_id_stamps[i].trimmed().isEmpty()))
		{
			QDomElement stamp = query_doc.createElement(WEBAPI_XML_STAMP);
			stamp.appendChild( query_doc.createTextNode( setup->enabled_channel_id_stamps[i].trimmed()));
			state_info.appendChild(stamp);
		}

		query.appendChild(state_info);

	}

	if (options)
	{
			

	// Put other options here.
	}
	
	// Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

	// Package up response.
	return xml_package_response(obj, result, (this->save_requests ? request_str : "" ), webapi_tvbatchlist);
}


webapi_error_t WebApiClient::tv_get_grid(webapi_tv_setup_t*  setup, webapi_query_options_t* options, QString start_date, QString end_date, webapi_object_t** obj)
{

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;

	int i;

	if ((obj == NULL) || (setup == NULL) || start_date.isEmpty() || end_date.isEmpty())
	{
		return WEBAPI_ERROR;
	}

	// Create QUERIES XML document with AUTH structure
	xml_create_queries_element( &query_doc, &queries_element);

	
	xml_add_query(&query_doc, &queries_element, &query, WEBAPI_XML_TVGRID_LOOKUP);

	if (setup->enabled_channel_ids.count() == 0)
	{
		return WEBAPI_ERROR;  //no channels enabled!
	}


	xml_add_channel_ids_to_query( &query_doc, &query, setup, WEBAPI_XML_GN_ID, WEBAPI_XML_TVCHANNEL);


	//add start and end date
	QDomElement start_date_element = query_doc.createElement(WEBAPI_XML_DATE);
	start_date_element.setAttribute( WEBAPI_XML_TYPE, WEBAPI_XML_START);
	start_date_element.appendChild( query_doc.createTextNode( start_date ));
	query.appendChild(start_date_element);

	QDomElement end_date_element = query_doc.createElement(WEBAPI_XML_DATE);
	end_date_element.setAttribute( WEBAPI_XML_TYPE, WEBAPI_XML_END);
	end_date_element.appendChild( query_doc.createTextNode( end_date ));
	query.appendChild(end_date_element);



	if (options)
	{
		
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}
		
		if ((!options->mode.isEmpty()) && (!options->input_gnid.isEmpty()))
		{
			xml_add_mode(&query_doc, &query, options->mode);
			QDomElement gnid = query_doc.createElement(WEBAPI_XML_GN_ID);
			gnid.appendChild(query_doc.createTextNode( options->input_gnid));
			query.appendChild(gnid);

		}

		// Put other options here.
	}
	
	// Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

	// Package up response.
	return xml_package_response(obj, result, (this->save_requests ? request_str : "" ), webapi_tvgrid);
}


// Get a TV program batch.
webapi_error_t WebApiClient::tv_get_program_batch(QString batch_url, webapi_object_t** obj)
{

	if (obj == NULL)
	{
		return WEBAPI_ERROR;
	}


	QUrl url = QUrl::fromEncoded(batch_url.toUtf8());
	QString fixed_url_str = url.toString();

	QByteArray qba = perform_http_get(fixed_url_str, NULL);
	QString result = QString::fromUtf8(qba.data());

	// Package up response.

	return xml_package_response(obj, result, "",  webapi_tvbatch);

}


// Get one TV program.
webapi_error_t WebApiClient::tv_get_program(webapi_tv_setup_t*  setup, webapi_object_type_t id_type, QString id ,  webapi_query_options_t* options, webapi_object_t** obj)
{
// Currently processing options is not implemented
	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;

	QDomElement id_xml;

	int i;

	if ((obj == NULL) || (id.isEmpty()))
	{
		return WEBAPI_ERROR;
	}

	// Create QUERIES XML document with AUTH structure
	xml_create_queries_element( &query_doc, &queries_element);

	if (id_type == webapi_tvprogram)
	{
		// Using TV program ID.

		xml_add_query(&query_doc, &queries_element, &query, WEBAPI_XML_TVPROGRAM_FETCH);
		id_xml = query_doc.createElement(WEBAPI_XML_GN_ID);
	}
	else 
	{
		// Assuming AV Work or Contributor ID.

		xml_add_query(&query_doc, &queries_element, &query, WEBAPI_XML_TVPROGRAM_LOOKUP);
		id_xml = query_doc.createElement(WEBAPI_XML_GN_ID);
		

#if 0
		// Add TV channel IDs.
		xml_add_channel_ids_to_query( &query_doc, &query, setup);
#endif
	}

	id_xml.appendChild(query_doc.createTextNode(id));
	query.appendChild(id_xml);


	// If query ID type is AV Work or Contributor, flag it.
	if (id_type == webapi_work)
	{
		xml_add_mode(&query_doc, &query, WEBAPI_XML_AV_WORK);
	}
	if (id_type == webapi_contributor)
	{
		xml_add_mode(&query_doc, &query, WEBAPI_XML_CONTRIBUTOR);
	}



	if (options)
	{

		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}
			
		// Put other options here.
	}
	
	// Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

	// Package up response.
	return xml_package_response(obj, result,(this->save_requests ? request_str : "" ),  webapi_tvbatch);


}


// Search for TV programs using a text string.
webapi_error_t WebApiClient::tv_search(webapi_tv_setup_t*  setup, webapi_query_options_t* options, QString text, QString start_date, QString end_date, webapi_object_t** obj)
{

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;


	if ((obj == NULL) || (setup == NULL) || start_date.isEmpty() || end_date.isEmpty())
	{
		return WEBAPI_ERROR;
	}

	// Create QUERIES XML document with AUTH structure
	xml_create_queries_element( &query_doc, &queries_element);

	
	xml_add_search(&query_doc, &queries_element, &query, webapi_tvprogram, WEBAPI_XML_TVPROGRAM_TITLE,text);

	if (setup->enabled_channel_ids.count() == 0)
	{
		return WEBAPI_ERROR;  //no channels enabled!
	}


	xml_add_channel_ids_to_query( &query_doc, &query, setup, WEBAPI_XML_GN_ID, WEBAPI_XML_TVCHANNEL);


	//add start and end date
	QDomElement start_date_element = query_doc.createElement(WEBAPI_XML_DATE);
	start_date_element.setAttribute( WEBAPI_XML_TYPE, WEBAPI_XML_START);
	start_date_element.appendChild( query_doc.createTextNode( start_date ));
	query.appendChild(start_date_element);

	QDomElement end_date_element = query_doc.createElement(WEBAPI_XML_DATE);
	end_date_element.setAttribute( WEBAPI_XML_TYPE, WEBAPI_XML_END);
	end_date_element.appendChild( query_doc.createTextNode( end_date ));
	query.appendChild(end_date_element);

	
	if (options)
	{
		
		if (!options->select_extended.isEmpty())
		{
			xml_add_query_option(&query_doc, &query, WEBAPI_XML_SELECT_EXTENDED, options->select_extended);
		}
		
		//add range if valid
		if (options->range_start > 0)
		{
			QDomElement xml_range_start;
			QDomElement xml_range_end;
			QDomElement xml_range;
			
			xml_range = query_doc.createElement(WEBAPI_XML_RANGE);
			
			xml_range_start = query_doc.createElement(WEBAPI_XML_START);
			xml_range_start.appendChild(query_doc.createTextNode( QString::number(options->range_start)));
			xml_range.appendChild(xml_range_start);
			
			xml_range_end = query_doc.createElement(WEBAPI_XML_END);
			xml_range_end.appendChild(query_doc.createTextNode( QString::number(options->range_end)));
			xml_range.appendChild(xml_range_end);

			query.appendChild(xml_range);
		}
		
	}
	




	// Perform network access.
	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

	// Package up response.
	return xml_package_response(obj, result, (this->save_requests ? request_str : "" ), webapi_tvgrid);
}



webapi_error_t WebApiClient::get_image_urls(QVector<QString> gn_ids, QString size, webapi_query_options_t* options, webapi_object_t** obj)
{

	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement query;
	int i;

	if ((obj == NULL) || (gn_ids.isEmpty()) || size.isEmpty() || (options == NULL) || options->mode.isEmpty()  )
	{
		return WEBAPI_ERROR;
	}

	xml_create_queries_element( &query_doc, &queries_element);
	xml_add_query(&query_doc, &queries_element, &query, "URL_GET");
	xml_add_mode(&query_doc, &query, options->mode);

	for (i=0; i<gn_ids.count(); i++)
	{

		QDomElement gnid = query_doc.createElement(WEBAPI_XML_GN_ID);
		gnid.appendChild(query_doc.createTextNode(  gn_ids[i] ));
		query.appendChild(gnid);

	}


	QString request_str = query_doc.toString();
	QString result = perform_http_post(service_url, request_str);

	// Package up response.
	return xml_package_response(obj, result,(this->save_requests ? request_str : "" ),  webapi_invalid);


}