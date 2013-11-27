/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#ifndef WEBAPI_REF_H
#define WEBAPI_REF_H

#include <QtGui/QWidget>
#include <QDomNode>
#include "ui_webapi_ref.h"
#include "webapiclient.h"

class history_element_t
{
public:
	QString         request_xml;
	QString         response_xml;
};

class webapi_ref : public QWidget
{
	Q_OBJECT

public:
	webapi_ref(QWidget *parent = 0, Qt::WFlags flags = 0);
	~webapi_ref();

public slots:
	// Set the font size fpr tree view.
	void tree_set_font();

	// Expand the tree level.
	void expand_tree_level();

	// Method triggered when back button is clicked.
	void go_back();

	// For doing text searches.
	void text_search();

	// For downloading images.
	void download_image(QString url);

	// Function to render data to the screen when a new item is looked
	// up or the back button is clicked.
	void render();

	// Looks up the current tree widget selection.
	void lookup_id();

	//Determines whether to hide or show some widgets
	void check_widgets_visibility(bool state);

	//Updates UI when tree selection changes
	void tree_selection_changed();

	// Looks up a zip code
	void lookup_zip();

	// Adds a new item to the lookup history so the user can go back.
	void push_new_lookup_history(history_element_t new_history_elt);

	// Take in a response XML blob from the response text box, convert to XML,
	// and then convert to the tree widget item.
	void response_to_treewidget();

	// Function to do the lookup if a selectable row is double-clicked.
	void tree_doubleclick(QTreeWidgetItem *item, int column);

private:
	Ui::webapi_refClass ui;

	QList<history_element_t> lookup_history;

	//Generates SELECT_EXTENDED string based on currently checked checkboxes
	QString generate_select_extended();

	void load_config_file();

	WebApiClient	webapiclient;

	QString		client_id;
	QString		user_id;
	QString		country;
	QString		lang;
	QString		service_url;

	//proxy support
	QString		proxy_url;
	QString		proxy_port;
	QString		proxy_user;
	QString		proxy_passwd;

};

#endif // WEBAPI_REF_H
