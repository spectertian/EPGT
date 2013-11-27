/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#define WEBAPI_RESPONSE1		"/RESPONSES/RESPONSE[1]"

#define WEBAPI_WORKS	 		QString("/AV_WORK")
//#define WEBAPI_WORK(WORK_N) 	("/AV_WORK[" + QString::number(WORK_N) +"]")

#define WEBAPI_SERIES	 		QString("/SERIES")
//#define WEBAPI_SERIES(SER_N) 	("/SERIES[" + QString::number(SER_N) +"]")

#define WEBAPI_CONTRIBUTORS	 		QString("/CONTRIBUTOR")
//#define WEBAPI_CONTRIBUTOR(CON_N) 	("/CONTRIBUTOR[" + QString::number(CON_N) +"]")


#define WEBAPI_NTH(NTH)			("[" + QString::number(NTH) + "]")
#define WEBAPI_NTH_BY_ORD(ORD)			("[@ORD=" + QString::number(ORD) + "]")
#define WEBAPI_NTH_BY_RANK(RANK)		("[@RANK=" + QString::number(RANK) + "]")



#define WEBAPI_PRIMARY_GENRE			"/GENRE[1]/text()"
#define WEBAPI_PRIMARY_PRODUCTION_TYPE	"/PRODUCTION_TYPE[1]/text()"

#define WEBAPI_ALBUM_GENRE				"/GENRE[1]/text()"


// AV Works and products have different xpaths for release date.
#define WEBAPI_WORK_RELEASE_DATE	"/DATE[@TYPE=\"ORIGINALRELEASE\"]/text()"


#define WEBAPI_HACK_MPAA_RATING		"/RATING[SYSTEM=\"MPAA\"]/CODE/text()"

#define WEBAPI_RANK_VALUE 				"/@RANK/string()"

#define WEBAPI_NAME					"/NAME/text()"
#define WEBAPI_TITLE				"/TITLE/text()"
#define WEBAPI_GN_ID				"/GN_ID/text()"

#define WEBAPI_DURATION				"/DURATION[1]/text()"
#define WEBAPI_SYNOPSIS				"/SYNOPSIS/text()"
#define WEBAPI_XID_BY_SOURCE(SOURCE)	("/XID[@DATASOURCE=\""+QString(SOURCE)+"\"]")

#define WEBAPI_CONTRIBUTION_TYPE_DIRECTOR		"15957"
#define WEBAPI_CONTRIBUTION_TYPE_PRODUCER		"15958"
#define WEBAPI_CONTRIBUTION_TYPE_SCREENWRITER	"15961"



#define WEBAPI_CONTRIBUTORS_BY_CONTRIBUTION_TYPE(C_TYPE)  ("/CONTRIBUTOR[CONTRIBUTION/CONTRIBUTION_TYPE/@ID=\""+QString(C_TYPE)+"\"]")

#define WEBAPI_CONTRIBUTOR_MEDIAGRAPHY	QString("/MEDIAGRAPHY")
#define WEBAPI_TOP_WORK					"/AV_WORK[@RANK=1]"
#define WEBAPI_TOP_SERIES				"/SERIES[@RANK=1]"

#define WEBAPI_CONTRIBUTION_CHARACTER 	"/CONTRIBUTION/CHARACTER/text()"
#define WEBAPI_CONTRIBUTION_ROLE		"/CONTRIBUTION/CONTRIBUTION_TYPE/text()"
#define WEBAPI_CONTRIBUTION_ROLE_ID     "/CONTRIBUTION/CONTRIBUTION_TYPE/@ID/string()"

#define WEBAPI_EDITION		"/EDITION/text()"
#define WEBAPI_PRODUCT		 QString("/VIDEODISCSET")

#define WEBAPI_BIRTH_DATE "/DATE[@TYPE=\"BIRTH\"]/text()"
#define WEBAPI_DEATH_DATE "/DATE[@TYPE=\"DEATH\"]/text()"
#define WEBAPI_BIOGRAPHY "/BIOGRAPHY/text()"

#define WEBAPI_ALBUMS QString("/ALBUM")
#define WEBAPI_ALBUM_DATE "/DATE/text()"
#define WEBAPI_ARTIST_NAME "/ARTIST/text()"

#define WEBAPI_TRACK_BY_NUM(N)	"/TRACK[TRACK_NUM=" + QString::number(N) + "]"
#define WEBAPI_TRACK_COUNT		"/TRACK_COUNT/text()"

#define WEBAPI_LYRIC QString("/LYRIC")

#define WEBAPI_LYRIC_BLOCKS "/BLOCK"
#define WEBAPI_LYRIC_BLOCK_BY_ORD(N)  ("/BLOCK[@ORD=" + QString::number(N) + "]")

#define WEBAPI_LYRIC_LINES "/LINE"
#define WEBAPI_LYRIC_LINE_BY_NUM(N)  ("/LINE[@NUM=" + QString::number(N) + "]")


