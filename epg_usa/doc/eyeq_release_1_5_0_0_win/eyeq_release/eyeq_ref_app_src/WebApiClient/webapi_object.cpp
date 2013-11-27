/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#include "webapi_types.h"
#include "webapi_object.h"
#include "webapiclient.h"

webapi_object::webapi_object()
{
}


webapi_object::~webapi_object()
{
}


// Web API Object Factory
webapi_error_t webapi_object::webapi_object_factory(webapi_object_t** p_obj, QString response_str, QString request_str, webapi_object_type_t type )
{

	// Takes a QString containing XML and wraps it in a structure.

	if (p_obj == NULL)
	{
		return WEBAPI_ERROR;
	}

	webapi_object* obj = new webapi_object;
	if (!obj)
	{
		return WEBAPI_ERROR;
	}
	

	obj->source_xml = response_str;
	obj->request_xml = request_str;
			
	obj->object_type = type;

	obj->refcount = 1;

	*p_obj = obj;

	return WEBAPI_OK;
}


void webapi_object::xml_object_retain()
{
	if (this->refcount <= 0)
	{
		assert(0); //what?
	}

	this->refcount++;
}

void webapi_object::xml_object_release()
{
	this->refcount--;

	if (this->refcount < 0)
	{
		assert(0);  // what?
	}

	if (this->refcount ==0)
	{
		delete this;  // Is this okay?
	}

}

webapi_error_t webapi_object::xml_request_string(QString* xml_out)
{
	if ( (xml_out == NULL) || (this->request_xml.isEmpty()))
	{
		return WEBAPI_ERROR;
	}
	
	*xml_out = this->request_xml;

	return WEBAPI_OK;
}


webapi_error_t webapi_object::xml_response_string(QString* xml_out)
{
	if (xml_out == NULL)
	{
		return WEBAPI_ERROR;
	}
	
	*xml_out = this->source_xml;

	return WEBAPI_OK;
}
