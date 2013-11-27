/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#include "webapi_object.h"
#include "webapi_object_xp.h"
#include <QVector>

void webapi_object_xp::test_method()
{

	printf(" called test method\n");

}

webapi_error_t webapi_object_xp::webapi_object_xp_factory(webapi_object** p_obj, QString response_str, QString request_str, webapi_object_type_t type )
{

	
	webapi_object_xp* obj = new webapi_object_xp;
	if (!obj)
	{
		return WEBAPI_ERROR;
	}
	

	obj->source_xml = response_str;
	obj->request_xml = request_str;
	

	//only for xpath version
	obj->qb.setData( response_str.toUtf8() );
	obj->qb.open(QIODevice::ReadOnly);

	obj->xq = QXmlQuery();
	obj->xq.bindVariable( "result", & (obj->qb));

	
	obj->object_type = type;

	obj->refcount = 1;

	*p_obj = obj;

	return WEBAPI_OK;
}


/*  This function takes in an xquery, and modifies it to return a value or count.

desired behavior of this function (anything else is an error)


If user asked for count and not value:
	if there are no values, return count=0,  webapi_ok
	if there are N values, return count = N, webapi_ok


If the user asked for value and not count:
	if there are at least 1 value, return 1st value, webapi_ok  (for more values, user must make xpath more specific)
	if there are no values, return webapi_error


If the user asked for both value and count:
	always return count
	if there are no values return error, otherwise return 1st value

*/



webapi_error_t webapi_object_xp::xml_get(QString xpath_in, QString* value, unsigned int* count )
{
	QString value_local;
	int count_local;
	QString count_str;
	QString paren_start="";
	QString xpath = xpath_in;

	//If we are fed in an xpath starting with parens, lets unwrap to the root, so we can insert our 'doc($result)' in the right place
	//FROM: (((/a/b)/c)/d)
	//TO:   (((doc($result)/a/b)/c)/d)

	while (xpath.left(1) == "(")
	{
		paren_start += "(";
		xpath = xpath.mid(1);
	}

	xpath = paren_start + "doc($result)" + xpath;
	




	



	//if user wanted count, return it
	if (count != NULL)
	{
		this->xq.setQuery("count("  +xpath + ")");		
		this->xq.evaluateTo(&count_str);
		count_local = count_str.toInt();

		*count = count_local;
	}

	
	//user wants value?
	if (value != NULL)
	{

		

		this->xq.setQuery(xpath);
		
		//this->xq.evaluateTo(&value_local);  //note:  this would be to easy.  special characters are returned escaped!

	

		QXmlResultItems res;
		this->xq.evaluateTo(&res);
		QXmlItem item(res.next());
		if (item.isNull())
		{
			return WEBAPI_ERROR;
		}
		else
		{
			if (item.isAtomicValue())
			{
				//If the xpath ends in /string() we end up here
				QVariant qv = item.toAtomicValue();
				value_local = qv.toString();
			}
			else if (item.isNode())  
			{
				//if the user xpath ends in /text() we are in a 'node' (a 'text' node) and end up here
				QXmlNodeModelIndex qi = item.toNodeModelIndex();
				value_local = qi.model()->stringValue(qi);
			}
			else
			{
				//I don't know what this case means, but its probably bad
				return WEBAPI_ERROR;
			}
		}
	
		*value = value_local.trimmed();
	}
	
	return WEBAPI_OK;
}



//set a xpath to be reused for several queries
webapi_error_t webapi_object_xp::xml_query_set( QString xpath_in)
{
	QString paren_start="";
	QString xpath = xpath_in;

	

	//count leading parenthesis
	while (xpath.left(1) == "(")
	{
		paren_start += "(";
		xpath = xpath.mid(1);
	}

	//insert the document root
	xpath = paren_start + "doc($result)" + xpath;
	
	this->pending_xpath = xpath;  //memorize the xpath for later use

	return WEBAPI_OK;
}


//set variables within the xpath
webapi_error_t webapi_object_xp::xml_query_set_var(QString name, int value)
{

	this->xq.bindVariable( name,  QVariant(value) );

	return WEBAPI_OK;
}


#if 0
//get result of the query
webapi_error_t WebApiClient::xml_query_get_value(webapi_object_t* obj, QString* value)
{
	if (!obj)	
		return WEBAPI_ERROR;

	QString value_local;
	QXmlResultItems res;

	if (!obj->pending_xpath.isEmpty())
	{
		obj->xq.setQuery(obj->pending_xpath);
		obj->pending_xpath = "";
	}

	obj->xq.evaluateTo(&res);
	QXmlItem item(res.next());
	
	if (!item.isNull())
	{
		if (item.isAtomicValue())
		{
			//If the xpath ends in /string() we end up here
			QVariant qv = item.toAtomicValue();
			value_local = qv.toString();
		}
		else if (item.isNode())  
		{
			//if the user xpath ends in /text() we are in a 'node' (a 'text' node) and end up here
			QXmlNodeModelIndex qi = item.toNodeModelIndex();
			value_local = qi.model()->stringValue(qi);
		}
		else
		{
			//I don't know what this case means, but its probably bad
			return WEBAPI_ERROR;
		}
	}

	*value = value_local.trimmed();


	return WEBAPI_OK;
}

#endif


webapi_error_t webapi_object_xp::xml_query_get_values( QVector<QString> *values, QString var_name, int start, int end)
{
	QString value_local;
	QXmlResultItems res;
	QString q = "for $" + var_name+ " in "+ QString::number(start) + " to " + QString::number(end) + "\n" ;
	q+= "let $xxx := ( "+this->pending_xpath+" ) \n";
	q+= "return  if ($xxx[1]) then (string($xxx[1])) else \"\"";
	

	//printf(" USING QUERY %s\n\n", q.toUtf8().data());

	this->xq.setQuery(q);
	this->xq.evaluateTo(&res);
	values->clear();

	while(1)
	{

		value_local = "";
		QXmlItem item(res.next());

		if (!item.isNull())
		{
			if (item.isAtomicValue())
			{
				//If the xpath ends in /string() we end up here
				QVariant qv = item.toAtomicValue();
				value_local = qv.toString();

			}
			else if (item.isNode())  
			{
				//if the user xpath ends in /text() we are in a 'node' (a 'text' node) and end up here
				QXmlNodeModelIndex qi = item.toNodeModelIndex();
				value_local = qi.model()->stringValue(qi);
			}
			else
			{
				//I don't know what this case means, but its probably bad
				return WEBAPI_ERROR;
			}

			values->append( value_local);
//			printf("%s -<\n", value_local.toUtf8().data());
		}
		else
		{
			//printf(" bad\n");
			break;
		}
		

	}


#if 0
	int i = 0;

	if (!values)	
		return WEBAPI_ERROR;

	values->clear();

	for (i=start; i<= end; i++)
	{
		QString value_local;
		QXmlResultItems res;

		obj->xq.bindVariable(var_name, QVariant(i));

		obj->xq.evaluateTo(&res);
		QXmlItem item(res.next());

		if (!item.isNull())
		{
			if (item.isAtomicValue())
			{
				//If the xpath ends in /string() we end up here
				QVariant qv = item.toAtomicValue();
				value_local = qv.toString();
			}
			else if (item.isNode())  
			{
				//if the user xpath ends in /text() we are in a 'node' (a 'text' node) and end up here
				QXmlNodeModelIndex qi = item.toNodeModelIndex();
				value_local = qi.model()->stringValue(qi);
			}
			else
			{
				//I don't know what this case means, but its probably bad
				return WEBAPI_ERROR;
			}
		}

		values->append( value_local);

	}
#endif

	if (values->count() == (end-start+1))
		return WEBAPI_OK;
	else
		return WEBAPI_ERROR;



}



webapi_object** webapi_object_xp::cast (webapi_object_xp** xp_pointer)
{

	return (webapi_object**) xp_pointer;
}