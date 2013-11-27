/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */


#include <QtCore/QCoreApplication>

//include a webapi_object header file
//#include "webapi_object.h"

//then a webapi client file
#include "webapiclient.h"
#include "webapi_object_xp.h"


#include <time.h>

#include <malloc.h>
#include <QXmlQuery>
#include <QBuffer>


void do_test(WebApiClient* webapiclient)
{

	/*Fetches  (Example work fetch): */

	webapi_error_t				error = WEBAPI_OK;
	webapi_object_xp*			wobj = NULL;		/*Holds the object retrieved from service*/
	webapi_query_options_t		options;			/*Holds query options*/
	QString						temp_string;
	unsigned int				count = 0;
	unsigned int				i = 0;

#if 1


	options.select_extended = "IMAGE,CONTRIBUTOR_IMAGE"; /*Example options: lets specify we want an Image returned, and a images for each Contributor in the credits.  These are not required*/

	error = webapiclient->fetch(webapi_work /*object type*/, "238706148-B4A25749DAEFD5BDD4F573471BD77B7F" /*GNID*/ , &options ,  (webapi_object**) &wobj );


	

	if (error == WEBAPI_OK)  /* if error code is successful */
	{
#if 1

		error = wobj->xml_get("/RESPONSES/RESPONSE/AV_WORK/TITLE/string()", &temp_string, NULL);
		if (error == WEBAPI_OK)
		{
			printf(" Work title: %s\n", temp_string.toUtf8().data());
		}

		/*get an image*/

		error = wobj->xml_get("/RESPONSES/RESPONSE/AV_WORK/URL[@TYPE=\"IMAGE\"]/string()", &temp_string, NULL);
		if (error == WEBAPI_OK)
		{
			printf(" Go to this URL to see an image:\n%s\n", temp_string.toUtf8().data());
		}
		
		/*parse other XML fields here*/

#endif

		wobj->xml_object_release();  /* release this object*/ 
	}
	else
	{
		printf("Error fetching work.\n");
	}

#endif

#if 0

	options.range_start = 1;  /*Specify results 1 to 10*/
	options.range_end = 10;

	options.select_extended = "IMAGE"; /*Example options: lets specify we want an Image returned for the contributor.  These are not required*/

	error = webapiclient->search(webapi_contributor /*object type*/, "NAME" /*Field*/ , "Mike", &options , &wobj );

	if (error == WEBAPI_OK)  /* if error code is successful */
	{

		/*Count how many results there are*/
		error = webapiclient->xml_get(wobj, "/RESPONSES/RESPONSE/CONTRIBUTOR", NULL, &count);

		for (i=1; i<= count; i++)
		{

			error = webapiclient->xml_get(wobj, "/RESPONSES/RESPONSE/CONTRIBUTOR[" + QString::number(i)  +"]/NAME/string()", &temp_string, NULL);
			if (error == WEBAPI_OK)
			{
				printf("\n\n%d.  Name: %s\n", i, temp_string.toUtf8().data());
			}

			/*get an image*/

			error = webapiclient->xml_get(wobj, "/RESPONSES/RESPONSE/CONTRIBUTOR[" + QString::number(i)  +"]/URL[@TYPE=\"IMAGE\"]/string()", &temp_string, NULL);
			if (error == WEBAPI_OK)
			{
				printf(" Go to this URL to see an image:\n%s\n", temp_string.toUtf8().data());
			}

			/*parse other XML fields here*/
		}


		webapiclient->xml_object_release(wobj);  /* release this object*/ 
	}
	else
	{
		printf("Error searching contributor.\n");
	}


#endif

#if 0

	/* sample dvd toc lookup */
	QString toc = "<DVD_THIN_TOC VER=\"1\" ANGLED=\"FALSE\"> <VIDEO_TS> <REGION>F2</REGION> <VTS_ATTR CNT=\"1\"> <VTS N=\"1\"> <VIDEO_ATTR> <COMPRESSION_MODE TYPE=\"1\"></COMPRESSION_MODE> <TV_SYSTEM TYPE=\"0\"></TV_SYSTEM> <ASPECT_RATIO TYPE=\"0\"></ASPECT_RATIO> <DISP_MODE TYPE=\"3\"></DISP_MODE> <CC_FIELD1 TYPE=\"1\"></CC_FIELD1> <CC_FIELD2 TYPE=\"0\"></CC_FIELD2> <SRC_PIC_RESOLUTION TYPE=\"0\"></SRC_PIC_RESOLUTION> <SRC_PIC_LETTERBOXED TYPE=\"0\"></SRC_PIC_LETTERBOXED> <FILM_CAMERA_MODE TYPE=\"0\"></FILM_CAMERA_MODE> </VIDEO_ATTR> <TITLE_UNIT_ARRAY CNT=\"14\"> <TITLE_UNIT N=\"1\" CNT=\"7\">40203 40225 40333 40110 40162 40320 40080</TITLE_UNIT> <TITLE_UNIT N=\"2\" CNT=\"1\">360</TITLE_UNIT> <TITLE_UNIT N=\"3\" CNT=\"1\">961</TITLE_UNIT> <TITLE_UNIT N=\"4\" CNT=\"1\">2004</TITLE_UNIT> <TITLE_UNIT N=\"5\" CNT=\"1\">899</TITLE_UNIT> <TITLE_UNIT N=\"6\" CNT=\"1\">40203</TITLE_UNIT> <TITLE_UNIT N=\"7\" CNT=\"1\">40225</TITLE_UNIT> <TITLE_UNIT N=\"8\" CNT=\"1\">40333</TITLE_UNIT> <TITLE_UNIT N=\"9\" CNT=\"1\">40110</TITLE_UNIT> <TITLE_UNIT N=\"10\" CNT=\"1\">40162</TITLE_UNIT> <TITLE_UNIT N=\"11\" CNT=\"1\">40320</TITLE_UNIT> <TITLE_UNIT N=\"12\" CNT=\"1\">40080</TITLE_UNIT> <TITLE_UNIT N=\"13\" CNT=\"1\">9060</TITLE_UNIT> <TITLE_UNIT N=\"14\" CNT=\"1\">40203</TITLE_UNIT> </TITLE_UNIT_ARRAY> </VTS> </VTS_ATTR> </VIDEO_TS> </DVD_THIN_TOC>";

	options.select_extended = "COVERART"; /* Lets get the cover art, if available*/

	error = webapiclient->toc_lookup( webapi_video_product, toc, &options, &wobj);


	if (error == WEBAPI_OK)
	{

		error = webapiclient->xml_get(wobj, "/RESPONSES/RESPONSE/VIDEODISCSET", NULL, &count);

		for (i=1; i<= count; i++)
		{

			error = webapiclient->xml_get(wobj, "/RESPONSES/RESPONSE/VIDEODISCSET[" + QString::number(i)  +"]/TITLE/string()", &temp_string, NULL);
			if (error == WEBAPI_OK)
			{
				printf("\n\n%d.  Title: %s\n", i, temp_string.toUtf8().data());
			}

			/*get an image*/

			error = webapiclient->xml_get(wobj, "/RESPONSES/RESPONSE/VIDEODISCSET[" + QString::number(i)  +"]/URL[@TYPE=\"COVERART\"]/string()", &temp_string, NULL);
			if (error == WEBAPI_OK)
			{
				printf(" Go to this URL to see an image:\n%s\n", temp_string.toUtf8().data());
			}

			/*parse other XML fields here*/
		}
		

		webapiclient->xml_object_release(wobj);
	}	
	else
	{
		printf("Error performing toc lookup\n");

	}

#endif

	exit(0);

}


int main(int argc, char *argv[])
{
	FILE* f = NULL;
	char * sample_query = NULL;
	webapi_error_t error = WEBAPI_OK;

	QCoreApplication a(argc, argv);

	WebApiClient myClient(webapi_object_xp::webapi_object_xp_factory);

//
//	myClient.perform_http_get("http://www.google.com");
	//myClient.perform_http_get("http://www.ericgiguere.com/tools/http-header-viewer.html");
//	myClient.perform_http_post("http://www.ericgiguere.com/tools/http-header-viewer.html","");
	

//	return 0;
//
	//error = myClient.initialize( "http://195.143.189.52/webapi/xml/1.0/", "5485824-EF98DD38AFFC115C9B1E2C8D59997501", "87060163804594181-1358C5501DE28CDFC5567DDA342E7622");
	error = myClient.initialize( "http://s3-dev-412.internal.gracenote.com/~bmink/test/", "5485824-EF98DD38AFFC115C9B1E2C8D59997501", "87060163804594181-1358C5501DE28CDFC5567DDA342E7622");
//	error = myClient.initialize( "https://c5485824.web.cddbp.net/webapi/xml/1.0/no_cdscheck/", "5485824-EF98DD38AFFC115C9B1E2C8D59997501", "87060163804594181-1358C5501DE28CDFC5567DDA342E7622");
//	error = myClient.initialize( "https://c5485824.web.cddbp.net/webapi/xml/1.0/", "5485824-EF98DD38AFFC115C9B1E2C8D59997501", "87060163804594181-1358C5501DE28CDFC5567DDA342E7622");

	if (error != WEBAPI_OK)
	{
		printf("Could not initialize\n");
		return 1;

	}


	unsigned int i;
	unsigned int count;
	QString val;
	QString val2;
	webapi_object_t* obj = NULL;
	webapi_query_options_t options;



	//myClient.set_preferred_language("ger");
	myClient.set_preferred_language("eng");
	//myClient.set_country("usa");

	do_test(&myClient);

//	error = myClient.tv_recommend(NULL, webapi_work, webapi_work, "238529999-9B9192DB63990BC2C631124DE6B57077", NULL, &obj);

	printf("reco\n");
//	error = myClient.tv_recommend(NULL, webapi_tvprogram, webapi_work, "55023500", NULL, &obj);


	//get recommended works for star wars
	//error = myClient.tv_recommend(NULL, webapi_work, webapi_work, "218141495-EAFE6B9A97E4FFF2386FD5D544E2D67A", NULL, &obj);

//	myClient.xml_object_release(obj);
//	obj = NULL;



#if 0

	QVector<QString> values;
	myClient.fetch(webapi_product, "FAKE", NULL, &obj);


	val = "";
	error = myClient.xml_get(obj, "(/TEST/BAR/FOOB)[3]/text()", &val, NULL);
	printf(" error: %d  return: %s\n", error, val.toUtf8().data());

	val = "";
	error = myClient.xml_get(obj, "(/TEST/BAR/FOOB)[3]/string()", &val, NULL);
	printf(" error: %d  return: %s\n", error, val.toUtf8().data());

	val = "";
	error = myClient.xml_get(obj, "/TEST/BAR/FOOB/text()", &val, NULL);
	printf(" error: %d  return: %s\n", error, val.toUtf8().data());

	val = "";
	error = myClient.xml_get(obj, "/TEST/BAR/FOOB/string()", &val, NULL);
	printf(" error: %d  return: %s\n", error, val.toUtf8().data());




	myClient.xml_query_set_var(obj, "i", 0);
	myClient.xml_query_set(obj, "(/TEST/BAR[$i]/FOO[1]/string())");
	myClient.xml_query_get_values(obj, &values, "i", 1, 4);


	for (i=0;i<values.count();i++)
	{
		printf(" %d: %s\n", i, values[i].toUtf8().data());
	}


	return 0;
#endif


#if 0






	//options.select_extended = "FULL_MEDIAGRAPHY,MEDIAGRAPHY_IMAGES";

	myClient.set_preferred_language("eng");
	myClient.set_country("usa");

	
	webapi_tv_setup_t tv_setup;

	tv_setup.postalcode = "94608";

	options.select_extended = "IMAGE";
	//tv_setup.onids.append("12291");
//	tv_setup.onids.append("2");

//	myClient.tv_get_channels(&tv_setup, &options, &obj);


//	return 0;

	
#if 1
	myClient.tv_get_providers(&tv_setup, NULL, &obj);

	myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROVIDER", NULL, &count);
	
	for (i=1;i<=count;i++)
	{
		QString provider, name, place;

		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROVIDER["+QString::number(i)+"]/TVPROVIDER_ID/text()",  &provider, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROVIDER["+QString::number(i)+"]/NAME/text()",  &name, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROVIDER["+QString::number(i)+"]/PLACE/text()",  &place, NULL);

		printf(" TV PROVIDER  %s %s %s\n", provider.toUtf8().data(), name.toUtf8().data(), place.toUtf8().data());

		
		if (i==1)
		{
			tv_setup.enabled_provider = provider;  //lets take the first provider
		}
	}
	myClient.xml_object_release(obj);
	obj = NULL;



	myClient.tv_get_channels(&tv_setup, &options, &obj);
	
	myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVCHANNEL", NULL, &count);

	for (i=1;i<=count;i++)
	{
		QString name, sname, channelid, num;

		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVCHANNEL["+QString::number(i)+"]/CHANNEL_NUM/text()",  &num, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVCHANNEL["+QString::number(i)+"]/TVCHANNEL_ID/text()",  &channelid, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVCHANNEL["+QString::number(i)+"]/NAME/text()",  &name, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVCHANNEL["+QString::number(i)+"]/NAME_SHORT/text()",  &sname, NULL);

		printf(" TV CHANNEL  %s %s %s %s\n", num.toUtf8().data(), channelid.toUtf8().data(), name.toUtf8().data(),sname.toUtf8().data());

	
	//	if (i==1)
	//		tv_setup.enabled_channel_ids.append(channelid);
	}

	
	
	myClient.xml_object_release(obj);
	obj = NULL;
#endif


#if 0
	tv_setup.enabled_channel_ids.append("2008");

	myClient.tv_get_program_batch_list(&tv_setup, NULL, &obj);

	error = myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROGRAMBATCH", NULL, &count);


	for (i=1; i<= count; i++)
	{
		QString channel_id;
		QString rev;
		QString start_date;
		QString batch_id;

		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROGRAMBATCH[@ORD=" + QString::number(i)+"]/TVCHANNEL_ID/text()", &channel_id, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROGRAMBATCH[@ORD=" + QString::number(i)+"]/REV/text()", &rev, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROGRAMBATCH[@ORD=" + QString::number(i)+"]/RANGE[@TYPE=\"DATE\"]/START/text()", &start_date, NULL);
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/TVPROGRAMBATCH[@ORD=" + QString::number(i)+"]/TVPROGRAMBATCH_ID/text()", &batch_id, NULL);

		printf("channel %s  date %s  rev  %s  batch %s\n", channel_id.toUtf8().data(), start_date.toUtf8().data(), rev.toUtf8().data(), batch_id.toUtf8().data());

		if (i==1)
		{
			webapi_object_t* bobj = NULL;
			error = myClient.tv_get_program_batch( batch_id, NULL, &bobj);
			if (bobj)
			{


				myClient.xml_object_release(bobj);
				bobj = NULL;
			}



		}



	}



	myClient.xml_object_release(obj);
	obj = NULL;


	
	tv_setup.enabled_channel_ids.append("225444,261340,210243,21657");

	//myClient.tv_get_program(&tv_setup, "53493936", webapi_tvprogram, NULL, &obj);
	//myClient.tv_get_program(&tv_setup,  webapi_work, "193405107-35CEB8C30984910098F37FF7AF68CCA", NULL, &obj);
	myClient.tv_get_program(&tv_setup, webapi_contributor,"193406609-9B4B51F9E442ED24739474A833004DB4", NULL, &obj);

	


	myClient.xml_object_release(obj);
	obj = NULL;

#endif

//	myClient.fetch( webapi_contributor, "202734957-0DEE320614C21765C0950B39BEA13718", &options, &obj);
	
	//myClient.search( webapi_contributor, "NAME", "Dick Wolf", &options, &obj);

	/*
	myClient.xml_get(obj, "/RESPONSES/RESPONSE/AV_WORK[0]/XID", NULL, &count);

	for (i = 1 ; i <= count; i++)
	{	
		myClient.xml_get(obj, "/RESPONSES/RESPONSE/AV_WORK[0]/XID["+QString::number(i)+"]/@DATASOURCE/string()", &val, NULL);	

		myClient.xml_get(obj, "/RESPONSES/RESPONSE/AV_WORK[0]/XID["+QString::number(i)+"]/text()", &val2, NULL);	

		printf("{%s : %s}\n", val.trimmed().toUtf8().data(), val2.trimmed().toUtf8().data());
	}
*/

	//myClient.xml_object_release(obj);
printf("done\n");


#if 0

	
	QDomDocument query_doc;
	QDomElement queries_element;
	QDomElement work_query;

	myClient.xml_create_queries_element( &query_doc, &queries_element);
	//myClient.xml_add_fetch(&query_doc, &queries_element, &work_query,  webapi_work,   "202767807-024D89248D0D7824C25377571209ABC9");
	myClient.xml_add_search(&query_doc, &queries_element, &work_query, webapi_work, "TITLE", "Dark");
	myClient.xml_add_query_option(&query_doc, &work_query, "SELECT_EXTENDED", "IMAGE,CONTRIBUTOR_IMAGE,VIDEODISCSET,VIDEODISCSET_COVERART,LINK,VIDEODISCSET_LINK");
	
//	myClient.xml_do_query();

	
	printf(" xml document: %s\n", query_doc.toString().toUtf8().data());

	QString result = myClient.perform_http_post("https://c5485824.web.cddbp.net/webapi/xml/1.0/no_cdscheck/", query_doc.toString());


	printf(" reply is %s\n", result.toUtf8().data());

	
	
		
	{
		webapi_object_t* obj = NULL;
		QString val = "";
		unsigned int count;
		int i;

		myClient.xml_package_response( &obj, result, webapi_work);
		

		myClient.xml_get(obj, "/RESPONSES/RESPONSE/AV_WORK/XID", NULL, &count);

		for (i = 1 ; i <= 8; i++)
		{
			
			myClient.xml_get(obj, "/RESPONSES/RESPONSE/AV_WORK/XID["+QString::number(i)+"]/@DATASOURCE/string()", &val, NULL);
			
			printf("{%s} ", val.trimmed().toUtf8().data());
		}

		

			
	}

#endif

	



	//now try programatically breaking down a result
#if 0
	printf("...");

	getc(stdin);

	QBuffer qb;
	qb.setData( result.toUtf8());
	qb.open(QIODevice::ReadOnly);
	
	QXmlQuery xq;
	
	xq.bindVariable( QString("mydocument"), &qb);
	xq.setQuery("doc($mydocument)/RESPONSES/RESPONSE/AV_WORK/XID[8]/string()"  );
	
	
	
	
	//xq.setQuery("doc($mydocument)
	

	QString qr;
	xq.evaluateTo( &qr);
	printf(" %s\n", qr.toUtf8().data());


	printf("///");
#endif

#if 0
//	myClient.testHttp("https://encrypted.google.com");

	f=fopen("sample.xml", "rb");
	if (f)
	{
		int len =0;
		int len2=0;

		fseek(f, 0, SEEK_END);
		len = ftell(f);
		fseek(f, 0, SEEK_SET);
		
		sample_query = (char*) malloc(len+1);

		len2 = fread(sample_query, 1, len, f);

		if (len != len2)
			printf(" read error\n");

		sample_query[len2] = 0;

		fclose(f);


		printf(" %s\n", sample_query);

		{
			time_t t = time(0);

			myClient.perform_http_post("https://c5485824.web.cddbp.net/webapi/xml/1.0/no_cdscheck/", sample_query);
			
		//	myClient.perform_http_post("http://s3-dev-412.internal.gracenote.com/~bmink/test/", sample_query);
			printf(" %d seconds\n", time(0)-t);

		}

		free(sample_query);

	}

#endif


	//run default event loop if needed:
//	return a.exec();
#endif

}
