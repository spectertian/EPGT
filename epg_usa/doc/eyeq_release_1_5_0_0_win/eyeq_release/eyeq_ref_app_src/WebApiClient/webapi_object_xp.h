/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#ifndef WEBAPIOBJECT_XP_H
#define WEBAPIOBJECT_XP_H


#include "webapi_object.h"

#include <QXmlQuery>
#include <QBuffer>
#include <QXmlResultItems>
#include <QXmlNodeModelIndex>
#include <QAbstractXmlNodeModel>


class webapi_object_xp : public webapi_object
{

	public:

	static void test_method();




	//not needed in base object
	QXmlQuery            xq;
	QString				 pending_xpath;  //an xpath that had not been set as a query
	QBuffer              qb;  			//response as a qbuffer



	static webapi_error_t webapi_object_xp_factory(webapi_object** p_obj, QString response_str, QString request_str, webapi_object_type_t type );


	//get a single value by xpath
	webapi_error_t xml_get(QString xpath, QString* value, unsigned int* count );

	//set a xpath to be reused for several queries
	webapi_error_t xml_query_set(QString xpath);

	//set variables within the xpath
	webapi_error_t xml_query_set_var(QString name, int value);

	//get result of the query
//	webapi_error_t xml_query_get_value(QString* value);

	//get many values
	webapi_error_t xml_query_get_values(QVector<QString> *values, QString var_name, int start, int end);

	static webapi_object** cast (webapi_object_xp** xp_pointer);

};




#endif
