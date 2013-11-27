/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#ifndef WEBAPIOBJECT_H
#define WEBAPIOBJECT_H

#include "webapi_types.h"
#include <QString>
#include "assert.h"



typedef class webapi_object webapi_object_t;

// This class defines an encapsulated XML object.
class webapi_object
{

public:
	webapi_object();
	virtual ~webapi_object();

	// This method returns a webapi_object containing a response string from the Gracenote eyeQ Web API.  
	// Optionally, the request string can be stored as well.  The returned object begins with a 
	// reference count of one.
	static webapi_error_t webapi_object_factory(webapi_object** p_obj, QString response_str, QString request_xml, webapi_object_type_t type );

	// XML calls
	void xml_object_retain(); // Increment reference count of XML object
	void xml_object_release();  // Decremenet reference count of an XML object.  Frees when reference count is zero.

	// Get the response document as a string (only if save_requestion query option was set).
	webapi_error_t xml_response_string(QString* xml_out);

	// Get the request document as a string (only if save_requestion query option was set).
	webapi_error_t xml_request_string(QString* xml_out);

	webapi_object_type_t object_type;

	protected:
	QString				 request_xml;	// (Optional) This is the XML sent out for this query.
	QString              source_xml;  	// This is the response XML
	int                  refcount;  	// This allows for reference counting.
};


#endif // for WEBAPIOBJECT_H
