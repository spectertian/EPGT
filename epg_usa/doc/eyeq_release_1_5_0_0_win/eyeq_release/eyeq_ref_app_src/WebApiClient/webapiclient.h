/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#ifndef WEBAPICLIENT_H
#define WEBAPICLIENT_H

#include "webapi_types.h"
#include "webapi_object.h"
#include <QString>
#include <QStringList>
#include <QNetworkReply>
#include <QDomNode>
#include <QBuffer>
#include <QVector>
#include <QNetworkProxy>

// HTTP_METRICS
// When this macro is defined, the webapiclient will keep a log file (http_metrics.txt) 
// counting the number of bytes downloaded per request.  Note, this is the UNCOMPRESSED
// size.  WebApiClient uses the Qt library, which uses gzip compression when available.
// Therefore the actual amount of bandwidth used may be considerably less than the size 
// recorded here.
#define HTTP_METRICS



//  LOG_REQUESTS 
//  When this macro is defined, the webapiclient will save all requests and responses 
//  to webapi_request.xml and webapi_response.xml, respectively.  Each request/response 
//  pair will overwrite the previous pair.  
#define LOG_REQUESTS



#ifdef HTTP_METRICS
#include <QMutex>
#endif


// Language codes. 

#define	WEBAPI_LANG_ENGLISH				"eng"
#define	WEBAPI_LANG_CHINESE_SIMP		"qtb"
#define	WEBAPI_LANG_CHINESE_TRAD		"qtd"
#define	WEBAPI_LANG_DUTCH				"dut"
#define	WEBAPI_LANG_FRENCH				"fre"
#define	WEBAPI_LANG_GERMAN				"ger"
#define	WEBAPI_LANG_ITALIAN				"ita"
#define	WEBAPI_LANG_JAPANESE			"jpn"
#define	WEBAPI_LANG_KOREAN				"kor"
#define	WEBAPI_LANG_PORTUGUESE_BRAZIL	"por"
#define	WEBAPI_LANG_RUSSIAN				"rus"
#define	WEBAPI_LANG_SPANISH				"spa"
#define	WEBAPI_LANG_SWEDISH				"swe"
#define	WEBAPI_LANG_THAI				"tha"
#define	WEBAPI_LANG_POLISH				"pol"
#define	WEBAPI_LANG_TURKISH				"tur"




//XML Values


#define WEBAPI_XML_AUTH                 "AUTH"
#define WEBAPI_XML_AV_WORK              "AV_WORK"
#define WEBAPI_XML_AV_WORK_FETCH        "AV_WORK_FETCH"
#define WEBAPI_XML_AV_WORK_SEARCH       "AV_WORK_SEARCH"
#define WEBAPI_XML_CLIENT               "CLIENT"
#define WEBAPI_XML_CMD                  "CMD"
#define WEBAPI_XML_CONTRIBUTOR          "CONTRIBUTOR"
#define WEBAPI_XML_CONTRIBUTOR_FETCH    "CONTRIBUTOR_FETCH"
#define WEBAPI_XML_CONTRIBUTOR_SEARCH   "CONTRIBUTOR_SEARCH"
#define WEBAPI_XML_COUNTRY              "COUNTRY"
#define WEBAPI_XML_DATA                 "DATA"
#define WEBAPI_XML_DATE                 "DATE"
#define WEBAPI_XML_END                  "END"
#define WEBAPI_XML_EXPAND_SERIES        "EXPAND_SERIES"
#define WEBAPI_XML_GN_ID                "GN_ID"
#define WEBAPI_XML_LANG                 "LANG"
#define WEBAPI_XML_MODE                 "MODE"
#define WEBAPI_XML_OK                   "OK"
#define WEBAPI_XML_ONID                 "ONID"
#define WEBAPI_XML_SID                  "SID"
#define WEBAPI_XML_TSID                 "TSID"
#define WEBAPI_XML_DVBIDS               "DVBIDS"
#define WEBAPI_XML_OPTION               "OPTION"
#define WEBAPI_XML_PARAMETER            "PARAMETER"
#define WEBAPI_XML_POSTALCODE           "POSTALCODE"
#define WEBAPI_XML_QUERIES              "QUERIES"
#define WEBAPI_XML_QUERY                "QUERY"
#define WEBAPI_XML_RANGE                "RANGE"
#define WEBAPI_XML_REGISTER             "REGISTER"
#define WEBAPI_XML_RESPONSE             "RESPONSE"
#define WEBAPI_XML_RESPONSES            "RESPONSES"
#define WEBAPI_XML_SEASON_FETCH         "SEASON_FETCH"
#define WEBAPI_XML_SELECT_EXTENDED      "SELECT_EXTENDED"
#define WEBAPI_XML_SERIES_FETCH         "SERIES_FETCH"
#define WEBAPI_XML_SERIES_SEARCH        "SERIES_SEARCH"
#define WEBAPI_XML_SINGLE_BEST          "SINGLE_BEST"
#define WEBAPI_XML_STAMP                "STAMP"
#define WEBAPI_XML_START                "START"
#define WEBAPI_XML_STATE_INFO           "STATE_INFO"
#define WEBAPI_XML_STATE_TYPE           "STATE_TYPE"
#define WEBAPI_XML_STATUS               "STATUS"
#define WEBAPI_XML_TEXT                 "TEXT"
#define WEBAPI_XML_TOC                  "TOC"
#define WEBAPI_XML_TVCHANNEL            "TVCHANNEL"
#define WEBAPI_XML_TVCHANNEL_ID         "TVCHANNEL_ID"
#define WEBAPI_XML_TVCHANNEL_LOOKUP     "TVCHANNEL_LOOKUP"
#define WEBAPI_XML_TVGRID_LOOKUP		"TVGRID_LOOKUP"
#define WEBAPI_XML_TVGRIDBATCH_UPDATE   "TVGRIDBATCH_UPDATE"
#define WEBAPI_XML_TVGRID_SEARCH		"TVGRID_SEARCH"
#define WEBAPI_XML_TVPROGRAM            "TVPROGRAM"
#define WEBAPI_XML_TVPROGRAM_FETCH      "TVPROGRAM_FETCH"
#define WEBAPI_XML_TVPROGRAM_ID         "TVPROGRAM_ID"
#define WEBAPI_XML_TVPROGRAM_LOOKUP     "TVPROGRAM_LOOKUP"
#define WEBAPI_XML_TVPROGRAM_SEARCH     "TVPROGRAM_SEARCH"
#define WEBAPI_XML_TVPROGRAM_TITLE		"TVPROGRAM_TITLE"
#define WEBAPI_XML_TVPROVIDER           "TVPROVIDER"
#define WEBAPI_XML_TVPROVIDER_LOOKUP    "TVPROVIDER_LOOKUP"
#define WEBAPI_XML_TYPE                 "TYPE"
#define WEBAPI_XML_USER                 "USER"
#define WEBAPI_XML_VALUE                "VALUE"
#define WEBAPI_XML_VERSION              "VERSION"
#define WEBAPI_XML_VIDEODISCSET_FETCH   "VIDEODISCSET_FETCH"
#define WEBAPI_XML_VIDEODISCSET_TOC     "VIDEODISCSET_TOC"



// The webapi_tv_setup_t structure holds the user's current TV setup state.
// This includes the postalcode, enabled provider ID, and list of enabled
// tv channels.  Any query that required access to this state, such as 
// a tv program title search, requires a pointer to one of these structures as
// an argument.

typedef struct webapi_tv_setup_s
{
	QString				postalcode;				// Required for north americam when looking up providers
	QString				enabled_provider;		// Required for north americam when looking up channels
	QVector<QString>	dvbs;					// Required for europe, when looking up channels
	QVector<QString>	enabled_channel_ids;	// TVCHANNEL GN_IDs enabled for this query
	QVector<QString>	enabled_channel_id_stamps;  //STAMP values for each channel GN_ID (optional)  If provided, must be provided for all channels
} webapi_tv_setup_t;


// This structure holds query options for a particular query.  For information on
// particular fields such as SELECT_EXTENDED and MODE, see the Gracenote Web API
// documentation.
class webapi_query_options_t
{
	public:

	webapi_query_options_t();
	~webapi_query_options_t();

	//Value put for select_extended here is send up to WEBAPI as the value for
	//the query xml OPTION/SELECT_EXTENDED.
	QString select_extended;

	// On contributor fetches, the optional value for expand_series is the GN_ID of
	// a Series object.  This has the effect of showing the contributor's Season and Episode
	// (Work) data, but only for the selected series.  If this is not specified, episodes
	// and seasons of a series will not be returned in a contributor's mediagraphy.  Instead,
	// only the high-level Series credit will be returned.  
	QString expand_series;  

	// The value for mode is sent up for some queries
	QString mode;

	//input_gnid is used if a query need an optional gn_id passed as a parameter 
	//(such as a av_work gn_id passed in a tvgrid request)
	QString input_gnid;

	// Range_start and range_end are optional values in text searches to control
	// paging of search results.  Values in 1-based and are inclusive.
	// Setting range_start to zero disables sending this option.
	int range_start;
	int range_end;

	// On Contributor or Work fetches, if tv_setup is non-null, the list of 
	// Channels listed in tv_setup.enabled_channel_ids is sent up in the query.
	// This has the effect of returning tvprograms representing future showings
	// of the work, or works containing the contributor.  The tvprograms are 
	// Returned inside the work or contributor response.
	webapi_tv_setup_t* tv_setup;  
};


class WebApiClient // : QObject
{
	
public:

// 
// Setup and Initialization
// 

// WebApiClient  (constructor)
// Summary:  The constructor for WebApiClient has one required parameter, a
//           factory method for webapi_object.  This is required so that 
//           webapi_object can be subclassed without affecting any code in 
//           WebApiClient itself. 
// 
//   
// Parameters: webapi_object_factory
//  
//             If an application subclasses webapi_object, they can pass a 
//             factory method for the derived class and WebApiClient will 
//             create instances of the derived class instead of the base 
//             class. If an application is to use the base webapi_object class,
//             invoke this constuctor as: 
// 
//             WebApiClient(webapi_object::webapi_object_factory)
// 
// Return Values: None

	WebApiClient(	 webapi_error_t (*webapi_object_factory)(	webapi_object_t** p_obj,
																QString response_str, 
																QString request_xml, 
																webapi_object_type_t type ));
	~WebApiClient();


// WebApiClient::initialize
// Summary: initialize sets up internal WebApiClient data fields.  This
//          function will also perform the Web Api command REGISTER if 
//          necessary.
//   
// Parameters:
//             service_url (Required): Service URL to use for the Gracenote Web API commands
//             client_id   (Required): Client ID for user (format CLIENTID-TAG)
//             user_id     (Optional): If user_id is not supplied, initialize
//                         will perform a REGISTER command.  The resulting
//                         user_id can be read with WebApiClient::get_user_id
// 
// Return Values: 
//                 WEBAPI_OK on successfull registration or if registration is not necessary
//                 WEBAPI_ERROR if registration unsuccessful

	webapi_error_t initialize(QString service_url, QString client_id, QString user_id);

// WebApiClient::get_user_id
// 
// Summary:  Returns the currently active user ID.
//   
// Parameters:  None
// 
// Return Values:  Current user_id as a QString.  Empty QString if userid isn't
//                 set.

	QString get_user_id();  // Returns current user ID

// WebApiClient::set_preferred_language
// 
// Summary:  Sets preferred languaue for the Gracenote Web API query results.
//   
// Parameters:  Language code.  See the Gracenote Web API Documentation.
// 
// Return Values: None

	void set_preferred_language(QString lang);

// WebApiClient::set_country
// 
// Summary:  Sets country for the Gracenote Web API queries.
//   
// Parameters:  country: Country Code.  See the Gracenote Web API Documentation.
// 
// Return Values:  None

	void set_country(QString country);

// WebApiClient::set_save_requests
// 
// Summary:  Enables or disabled the option to save the Gracenote Web API query XML in a
//           webapi_object.  If set to true, webapi_object::xml_request_string
//           may be called to retrieve the exact query XML send to Service.
//   
// Parameters:  value:  True or False.  False is default value.
// 
// Return Values:  None

	void set_save_requests(bool value);  
	
// 
// Video Explore Query Calls
//  

// WebApiClient::fetch
// 
// Summary:  Retrieves a video explore object by its GN_ID
//   
// Parameters:
// 
//     type: (Required) Must be webapi_work, webapi_series, webapi_season, 
//           webapi_product, or webapi_contributor.
// 
//     gnid: (Required) GNID of the object requested.
// 
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t fetch     (webapi_object_type_t type, QString gnid, webapi_query_options_t*, webapi_object_t** obj);


// WebApiClient::search
// 
// Summary:  Searches for a video explore object by NAME or TITLE
//   
// Parameters:
// 
//     type: (Required) Must be webapi_work, webapi_series,  
//                      or webapi_contributor.
// 
//     field: (Required) Value passed to the Gracenote Web API for the FIELD name tag.
//     Typically NAME for contributor and TITLE for everything else.  See WEBAPI
//     documentation.
// 
//     value: (Required) String value being searched for 
// 
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail


	webapi_error_t search    (webapi_object_type_t type, QString field, QString value, webapi_query_options_t*, webapi_object_t** obj);


// WebApiClient::toc_lookup
// 
// Summary:  Retrieves a DVD or Blu-ray by disc TOC.
//   
// Parameters:
// 
//     type: (Required) Must be webapi_video_product (DVD or BD)
// 
//     toc: (Required) TOC string 
// 
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail


	webapi_error_t toc_lookup(webapi_object_type_t type, QString toc,  webapi_query_options_t*, webapi_object_t** obj);




//
// EPG/TV functions
// 

// WebApiClient::tv_get_program
// 
// Summary:  Retrieves a tvprogram by ID.
//   
// Parameters:
// 
//     setup: (Required) A pointer to valid tv_setup structure.
// 
//     id_type: (Required) Must be webapi_tvprogram
// 
//     id: (Required)  TVPROGRAM GN_ID for the tvprogram.
// 
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t tv_get_program  (webapi_tv_setup_t*  setup, webapi_object_type_t id_type, QString id ,  webapi_query_options_t* options, webapi_object_t** obj);


// WebApiClient::tv_get_providers
// 
// Summary:  Retrieves a list of TV providers given a postal code.
//   
// Parameters:
// 
//     setup: (Required) A pointer to valid tv_setup structure.
//                       tv_setup.postalcode must be specified.
// 
// 
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t tv_get_providers(webapi_tv_setup_t* setup, webapi_query_options_t*, webapi_object_t** obj);



// WebApiClient::tv_get_channels
// 
// Summary:  Retrieves a list of TV channels given a TVPROVIDER_ID or ONID.
//   
// Parameters:
// 
//     setup: (Required) A pointer to valid webapi_tv_setup_t structure.
//            For North America, setup.enabled_provider_id and 
//            setup.postalcode must be valid
// 
//           For Europe, setup.dvbs must have at least one DVB trilet in the form dvb://x.x.x  
// 
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
//                    Setting query_options.select_extended to "IMAGE" will
//                    result in tv channel logos being returned.
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t tv_get_channels (webapi_tv_setup_t* setup, webapi_query_options_t*, webapi_object_t** obj);


// WebApiClient::tv_get_program_batch_list
// 
// Summary:  Retrieves a list of TV program batches given a list of tv channels
//   
// Parameters:
// 
//     setup: 
	
// (Required) A pointer to valid webapi_tv_setup_t structure. There must be at 
// least one TVCHANNEL GN_ID specified in setup.enabled_channel_ids. The 
// Gracenote Web API may have a limit on the maximum number of IDs that may be 
// sent up at once
// 
// If only the GN_ID is sent up, the return will be always carry the instruction 'MUST_RELOAD' which specifies a full data batch will be downloaded.
// 
// To request an incremental update to a batch, specify STAMP values in setup.enabled_channel_id_stamps.
// 
//  Note the following rules:
// 
//    - The Nth member of enabled_channel_id_stamps is assumed to be the STAMP value for the Nth member of enabled_channel_ids.
// 
//    -  The number of members in enabled_channel_id_stamps must equal the number of  members in enabled_channel_ids, OR enabled_channel_id_stamps must be empty.
// 
//    -  Any member of enabled_channel_id_stamps may be an empty QString, if for instance, your application does not have a stamp value for a particular channel.
// 		
// 
//           
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t tv_get_program_batch_list(webapi_tv_setup_t*  setup, webapi_query_options_t* options, webapi_object_t** obj);


// WebApiClient::tv_get_program_batch
// 
// Summary:  Retrieves a batch of tvprograms
//   
// Parameters:
// 

//     batch_url: (required) A TV Program batch URL taken from a response returned by WebApiClient::tv_get_program_batch_list  
//           
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t tv_get_program_batch(QString batch_url, webapi_object_t** obj);


// WebApiClient::tv_get_grid
//
// Summary: Retrieves tv grid information for the requested channels and time.  
// A TVPROGRAM is returned if it any part of it airs on the selected channels 
// between the specified start and end times.  
//
// Note: if a tv programs ends at the exact time specified for the grid start 
// time, it is not considered as airing during that time.
//
// Parameters: 
//
//
// 	setup:		(Required) A pointer to valid webapi_tv_setup_t structure. 
// 				There must be at least one tvchannel GN_ID in the 
// 				setup.enabled_channel_ids list.
//
//  obj:		(Required) Pointer to (webapi_object*). Result is returned 
//  			through this pointer.
//
//  query_options:  (Optional) 
//
//  	Setting query_options.select_extended to "TVPROGRAM_IMAGE" will 
//  	result in image URLS returned for each tvprogram int the grid that has
//  	an available image.
//
//  	Addionally query_options.mode can optionally be set to different values :
//
//			mode = "CONTRIBUTOR" : If  options.gn_id contains a contributor GN_ID,
//			  then the returned tvgrid will only contains tv programs that reference 
//			  this contributor.
//
//			mode = "AV_WORK":  If options.gn_id contains a work GN_ID, then the
//			   returned tvgrid will only contain tv programs that reference this work.
//
//
//  start_date:  The date and time to start the tv grid.  
//  			 Time is in the format YYYY-MM-DDThh:mm , and is in UTC.
//				 example:  2011-01-13T01:02
//  
//
//  end_date:    End date and time for the grid, in UTC, same format as above.
//
//
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail

	webapi_error_t tv_get_grid(webapi_tv_setup_t*  setup, webapi_query_options_t* options, QString start_date, QString end_date, webapi_object_t** obj);

// WebApiClient::tv_search
// 
// Summary:  Searches tv listings for the specified input string.
//   
// Parameters:
// 
//     setup: (Required) A pointer to valid webapi_tv_setup_t structure.
//     		  There must be at least one TVCHANNEL  GN_ID specified in
//     		  setup.enabled_channel_ids. 
// 
//	  start_date: start date to begin search in format YYYY-MM-DDThh:mm
//   	          Date is in UTC.
// 
//	  end_date: end date for search
//
//     value: (Required) Text value being searched for.
//           
//     query_options: (Optional)  Specifies query options for this query (See
//                    documentation for webapi_query_option_t)
// 
//     obj:  (Required)  Pointer to (webapi_object*).  Result is returned
//           through this pointer.
// 
// Return Values:  
// 
//     WEBAPI_OK on success, WEBAPI_ERROR on fail


	webapi_error_t tv_search(webapi_tv_setup_t*  setup, webapi_query_options_t* options, QString text, QString start_date, QString end_date, webapi_object_t** obj);



	//url get call
	webapi_error_t get_image_urls(QVector<QString> gn_ids, QString size, webapi_query_options_t* options, webapi_object_t** obj);

	
	// 
	// network calls
	// 
#ifdef HTTP_METRICS
	QByteArray perform_http_get_metered(QString tag, QString url, int *return_code);
	QString perform_http_post_metered(QString tag, QString url, QString post_data);

	QMutex metric_mutex;
	QMap<QString, int> metrics;

#else
	QByteArray perform_http_get(QString url,  int *return_code);
	QString perform_http_post(QString url, QString post_data
#endif



	void set_proxy(QString proxy_url, int proxy_port, QString proxy_user, QString proxy_passwd);


private slots:
	void process_net_reply(QNetworkReply* net_reply);


private:
	bool save_requests;		// Set to true to save the request XML in a the object response

	QString service_url;
	QString client_id;		// Includes tag
	QString user_id;		// Includes tag
	QString preferred_language;
	QString country;
	
	QNetworkProxy  http_proxy;

	// Calls to create or extend queries
	webapi_error_t xml_create_queries_element(QDomDocument* doc, QDomElement* queries_element);
	webapi_error_t xml_add_auth_element(QDomDocument* doc, QDomElement* queries);
	webapi_error_t xml_add_query(QDomDocument* doc, QDomElement* queries, QDomElement* query, QString command);
	webapi_error_t xml_add_fetch(QDomDocument* doc, QDomElement* queries, QDomElement* query, webapi_object_type_t,  QString object_id);
	webapi_error_t xml_add_query_option( QDomDocument* doc, QDomElement* query, QString parameter_str, QString value_str);
	webapi_error_t xml_add_mode( QDomDocument* doc, QDomElement* query, QString mode_str);
	webapi_error_t xml_add_search(QDomDocument* doc, QDomElement* queries, QDomElement* query, webapi_object_type_t, QString field, QString value);
	webapi_error_t xml_add_toc_lookup(QDomDocument* doc, QDomElement* queries, QDomElement* query,	webapi_object_type_t fetch_type, QString toc);
	webapi_error_t xml_add_channel_ids_to_query( QDomDocument* query_doc, QDomElement* query, webapi_tv_setup_t* setup, QString channel_element_name, QString wrapper_element_name);
	
	// Call to package queries into objects
	webapi_error_t (*xml_package_response)(webapi_object_t** p_obj, QString response_str, QString request_xml, webapi_object_type_t type );

};





#ifdef HTTP_METRICS

#define perform_http_post(AAA,BBB) perform_http_post_metered(__FUNCTION__ , AAA, BBB)
#define perform_http_get(AAA,RRR) perform_http_get_metered(__FUNCTION__ , AAA,RRR)

extern QString http_metrics_filename;   // Allows user to override http metrics output filename

#else

#define perform_http_post_metered(TTT,AAA,BBB)	perform_http_post(AAA,BBB)
#define perform_http_get_metered(TTT,AAA,RRR)		perform_http_get(AAA,RRR)

#endif

#endif // WEBAPICLIENT_H
