/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#include "webapiclient.h"

// This file creates Gracenote Web API queries using qt QDomDocument

webapi_error_t WebApiClient::xml_create_queries_element(QDomDocument* doc, QDomElement* queries_element)
{
	// Creates a new QDomDocument containing one top level element named QUERIES.  Returns the document and the QUERIES element.
	//The QUERIES element also contains an AUTH element, if we have a userid

	if ( (doc == NULL) || (queries_element == NULL))
		return WEBAPI_ERROR;

	*doc = QDomDocument();
	*queries_element = doc->createElement(WEBAPI_XML_QUERIES);
	doc->appendChild(*queries_element);


	/* Add language */

	if (!preferred_language.isEmpty())
	{
		QDomElement lang = doc->createElement(WEBAPI_XML_LANG);
		lang.appendChild(doc->createTextNode(preferred_language));
		queries_element->appendChild(lang);
	}

	// Add country
	if (!country.isEmpty())
	{
		QDomElement xml_country = doc->createElement(WEBAPI_XML_COUNTRY);
		xml_country.appendChild(doc->createTextNode(country));
		queries_element->appendChild(xml_country);
	}

	/* Add an AUTH structure to the set of queries, if we have a user id*/

	if (!user_id.isEmpty())
	{
		return xml_add_auth_element(doc, queries_element);
	}

	return WEBAPI_OK;

}

webapi_error_t WebApiClient::xml_add_auth_element(QDomDocument* doc, QDomElement* queries)
{
	/*Adds an AUTH structure to a QUERIES structure*/
	QDomText client_id_text = QDomText();
	QDomText user_id_text = QDomText();

	if ( (doc == NULL) || (queries == NULL))
		return WEBAPI_ERROR;

	QDomElement client_id_element = doc->createElement(WEBAPI_XML_CLIENT);
	client_id_element.appendChild( doc->createTextNode(client_id));
	
	QDomElement user_id_element = doc->createElement(WEBAPI_XML_USER);
	user_id_element.appendChild( doc->createTextNode(user_id));

	QDomElement auth = doc->createElement(WEBAPI_XML_AUTH);

	auth.appendChild(client_id_element);
	auth.appendChild(user_id_element);

	queries->appendChild(auth);
	
	return WEBAPI_OK;
}

webapi_error_t WebApiClient::xml_add_query(QDomDocument* doc, QDomElement* queries, QDomElement* query, QString command)
{
	// Adds a blank query to a QUERIES structure

	if ( (doc == NULL) || (queries == NULL) || command.isEmpty() )
		return WEBAPI_ERROR;

	*query = doc->createElement(WEBAPI_XML_QUERY);
	
	if (!command.isEmpty())
	{
		//Adds a command
		query->setAttribute(WEBAPI_XML_CMD, command);
	}

	queries->appendChild(*query);

	return WEBAPI_OK;
}


webapi_error_t WebApiClient::xml_add_toc_lookup(
	QDomDocument* doc,
	QDomElement* queries,
	QDomElement* query,
	webapi_object_type_t fetch_type,
	QString toc
	)
{
	//Adds an object fetch query to a queries document
	char *command = NULL;
	char *toc_subtag_name = NULL;

	if ( (doc == NULL) || (queries == NULL) || (query == NULL) || toc.isEmpty())
		return WEBAPI_ERROR;


	if  (fetch_type == webapi_video_product)
	{
		command = WEBAPI_XML_VIDEODISCSET_TOC;
		toc_subtag_name = WEBAPI_XML_DATA;
	}
	else
	{
		return WEBAPI_ERROR;  //invalid fetch type for toc
	}

		
	xml_add_query(doc, queries, query, command);


	// Same thing for toc
	QDomElement toc_element = doc->createElement(WEBAPI_XML_TOC);
	QDomElement data_element = doc->createElement(toc_subtag_name);

	data_element.appendChild(doc->createTextNode(toc));
	toc_element.appendChild(data_element);

	query->appendChild(toc_element);

	return WEBAPI_OK;
}

webapi_error_t WebApiClient::xml_add_fetch(QDomDocument* doc, QDomElement* queries, QDomElement* query, webapi_object_type_t type, QString object_id)
{
	//Adds an object fetch query to a queries document
	char * command = "";


	if ( (doc == NULL) || (queries == NULL) || (query == NULL) || (object_id.isEmpty()))
		return WEBAPI_ERROR;


	switch(type)
	{
		case webapi_work:
		command = WEBAPI_XML_AV_WORK_FETCH;
			break;

		case webapi_series:
		command = WEBAPI_XML_SERIES_FETCH;
			break;

		case webapi_season:
		command = WEBAPI_XML_SEASON_FETCH;
			break;

		case webapi_product:
		command = WEBAPI_XML_VIDEODISCSET_FETCH;
			break;

		case webapi_contributor:
		command = WEBAPI_XML_CONTRIBUTOR_FETCH;
			break;

		default:
			return WEBAPI_ERROR;
	}

	xml_add_query(doc, queries, query, command);

	QDomElement gnid = doc->createElement(WEBAPI_XML_GN_ID);
	gnid.appendChild(doc->createTextNode( object_id));

	query->appendChild(gnid);

	return WEBAPI_OK;
}

webapi_error_t WebApiClient::xml_add_search(QDomDocument* doc, QDomElement* queries, QDomElement* query, webapi_object_type_t type, QString field, QString value)
{
	//Adds a search query to a queries document
	char * command = "";

	if ( (doc == NULL) || (queries == NULL) || (query == NULL) || (field.isEmpty() || (value.isEmpty())))
		return WEBAPI_ERROR;

	switch(type)
	{
		case webapi_work:
		command = WEBAPI_XML_AV_WORK_SEARCH;
		break;

		case webapi_series:
		command = WEBAPI_XML_SERIES_SEARCH;
		break;
	
		case webapi_contributor:
		command = WEBAPI_XML_CONTRIBUTOR_SEARCH;
		break;

		case webapi_tvprogram:
		command = WEBAPI_XML_TVGRID_SEARCH;
		break;

	default:
	return WEBAPI_ERROR;
	}

	xml_add_query(doc, queries, query, command);

	QDomElement text_element = doc->createElement(WEBAPI_XML_TEXT);
	text_element.setAttribute(WEBAPI_XML_TYPE, field);
	text_element.appendChild( doc->createTextNode(value));

	query->appendChild(text_element);
	
	return WEBAPI_OK;
}

webapi_error_t WebApiClient::xml_add_query_option( QDomDocument* doc, QDomElement* query, QString parameter_str, QString value_str)
{

	if ( (doc == NULL) || (query == NULL) || (parameter_str.isEmpty()) || (value_str.isEmpty()))
		return WEBAPI_ERROR;
	
	//Adds a query option to a query
	QDomElement option = doc->createElement(WEBAPI_XML_OPTION);

	QDomElement parameter = doc->createElement( WEBAPI_XML_PARAMETER);
	parameter.appendChild( doc->createTextNode(parameter_str));

	QDomElement value = doc->createElement(WEBAPI_XML_VALUE);
	value.appendChild(doc->createTextNode(value_str));

	option.appendChild(parameter);
	option.appendChild(value);

	query->appendChild(option);

	return WEBAPI_OK;
}

webapi_error_t WebApiClient::xml_add_mode( QDomDocument* doc, QDomElement* query, QString mode_str)
{
	//Adds a mode element to a query

	if ( (doc == NULL) || (query == NULL) || (mode_str.isEmpty()) )
		return WEBAPI_ERROR;
	

	QDomElement mode = doc->createElement(WEBAPI_XML_MODE);
	mode.appendChild( doc->createTextNode(mode_str));
	query->appendChild(mode);

	return WEBAPI_OK;
}


webapi_error_t WebApiClient::xml_add_channel_ids_to_query( QDomDocument* query_doc, QDomElement* query, webapi_tv_setup_t* setup, QString channel_element_name, QString wrapper_element_name)
{
	//Adds tv channel ids to a query

	unsigned int i;

	QDomElement* apply_channels_to = NULL;
	QDomElement  wrapper_element;

	if ( (query_doc == NULL) || (query == NULL) || (setup == NULL) || (channel_element_name.isEmpty()) )
		return WEBAPI_ERROR;

	if (! wrapper_element_name.isEmpty())
	{
		wrapper_element =  query_doc->createElement(wrapper_element_name);
		query->appendChild(wrapper_element);
		apply_channels_to = & wrapper_element;
	}
	else
	{	
		apply_channels_to = query;
	}


	for (i=0;i<setup->enabled_channel_ids.count();i++)
	{
		QDomElement tv_channel_id_xml = query_doc->createElement(channel_element_name);  
		tv_channel_id_xml.appendChild(query_doc->createTextNode(setup->enabled_channel_ids[i]));
		apply_channels_to->appendChild(tv_channel_id_xml);
	}

	return WEBAPI_OK;
}
