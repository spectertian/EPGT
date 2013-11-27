/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#ifndef WEBAPIERRORS_H
#define WEBAPIERRORS_H

typedef unsigned int webapi_error_t;

// Error Codes
#define WEBAPI_OK	 0
#define WEBAPI_ERROR 1

// Object Types
typedef enum {
	webapi_invalid = 0,
	webapi_product,
	webapi_work,
	webapi_series,
	webapi_season,
	webapi_contributor,
	webapi_video_product,
	webapi_fp_fpx,
	webapi_tvproviders,
	webapi_tvchannels,
	webapi_tvbatchlist,
	webapi_tvbatch,
	webapi_tvprogram,
	webapi_tvgrid
} webapi_object_type_t;

#endif // for WEBAPIERRORS_H