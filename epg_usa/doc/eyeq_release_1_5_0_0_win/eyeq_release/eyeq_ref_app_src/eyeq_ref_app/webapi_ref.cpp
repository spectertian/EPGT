/*
 * Copyright (c) 2011 Gracenote, Inc. All rights reserved.
 *
 * This software may not be used in any way or distributed without
 * Gracenote's written permission. All rights reserved.
 *
 * Some code herein may be covered by US and international patents.
 *
 */

#include "webapi_ref.h"
#include <QtGui/QFontDialog>
#include <QMessageBox>
#include <QFile>
#include <QDateTime>
#include <QMap>
#include <QTableWidget>
#include <QTableWidgetItem>
#include <QSslSocket>


// Web API Ref constructor. 
webapi_ref::webapi_ref(QWidget *parent, Qt::WFlags flags)
: QWidget(parent, flags) , webapiclient(webapi_object::webapi_object_factory)
{
	webapi_error_t error = WEBAPI_OK;

	ui.setupUi(this);

	// Set up font sizes and levels in tree view.
	connect(ui.font_pushButton, SIGNAL(clicked()), this, SLOT(tree_set_font()));

	connect(ui.level_expand_spinBox, SIGNAL(valueChanged(int)), this, SLOT(expand_tree_level()));

	connect(ui.back_pushButton, SIGNAL(clicked()), this, SLOT(go_back()));

	// Set up for text searches.
	connect(ui.textsearch_search_pushButton, SIGNAL(clicked()), this, SLOT(text_search()));
	connect(ui.search_lineEdit, SIGNAL(returnPressed()), this, SLOT(text_search()));


	connect(ui.result_treeWidget, SIGNAL(itemSelectionChanged()), this, SLOT(tree_selection_changed()));

	connect(ui.result_treeWidget, SIGNAL(itemDoubleClicked(QTreeWidgetItem *, int)), this, SLOT(tree_doubleclick(QTreeWidgetItem *, int)));

	connect(ui.search_live_tv_radioButton, SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radiobutton_tv_batch_url,   SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radiobutton_tv_grid,		   SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radiobutton_tv_batch_update,SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radiobutton_tv_channel,	   SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radioButton_contributor,    SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));

	connect(ui.radio_tvgrid_mode_none,		  SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radio_tvgrid_mode_av_work,	  SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radio_tvgrid_mode_contributor, SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radio_tvgrid_mode_series,	  SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));
	connect(ui.radio_tvgrid_mode_season,	  SIGNAL(toggled(bool)), this, SLOT(check_widgets_visibility(bool)));



	// Set up for fetches by selected ID.
	connect(ui.lookup_id_pushButton, SIGNAL(clicked()), this, SLOT(lookup_id()));
	connect(ui.id_lineEdit, SIGNAL(returnPressed()), this, SLOT(lookup_id()));

	// Set up for postal code lookup to select TV providers.
	connect(ui.pushButton_zip_lookup, SIGNAL(clicked()), this, SLOT(lookup_zip()));
	connect(ui.lineEdit_zip_code, SIGNAL(returnPressed()), this, SLOT(lookup_zip()));

	// Read the config.txt and user.txt file.
	load_config_file();

	if (!QSslSocket::supportsSsl())
	{
		//Check with QSslSocket if SSL is currently supported
		//We are not actually using QSslSocket for connections, we are using QNetworkAccessManager,
		//but the method of detecting SSL support this way is much easier.

		QMessageBox::information(this, "ERROR", "SSL is not supported.  Please install OpenSSL.");	
	}

	// Initialize the Web API client using the config.txt settings.
	error = webapiclient.initialize(
		service_url,
		client_id,
		user_id
		);


	//Set up proxy, if we have valid data
	if ((!proxy_url.isEmpty()) && (!proxy_port.isEmpty()))
	{
		webapiclient.set_proxy( proxy_url, proxy_port.toInt(), proxy_user, proxy_passwd);
	}

	if (error != WEBAPI_OK)
	{
		QMessageBox::information(this, "Registration", "Error registering new user.");
	}

	if (user_id.isEmpty())
	{
		//We did not have a userid, so initialize above should have registered one, if it was successful

		user_id = webapiclient.get_user_id();

		if (user_id.isEmpty())
		{
			QMessageBox::information(this, "Error", "Could not initialize.  Application does not have user_id set.");
		}
		else
		{
			QFile user_file("user.txt");

			if (user_file.open(QIODevice::WriteOnly))
			{
				user_file.write(  (user_id + "\n").toUtf8() );
				user_file.close();
			}
		}

	}

	// default country is usa if none specified.
	if (country.isEmpty())
	{
		country = "usa";
	}

	webapiclient.set_country(country); 
	
	if (!lang.isEmpty())
	{
		webapiclient.set_preferred_language(lang); 
	}

	webapiclient.set_save_requests(true);  // Keep the XML of all requests made.

	// Set the tree column widths to each be 1/3 the width of the tree.
	// Otherwise they are too narrow by default and need to be resized
	// by the user.
	ui.result_treeWidget->header()->resizeSection(0, ui.result_treeWidget->width() / 3);
	ui.result_treeWidget->header()->resizeSection(1, ui.result_treeWidget->width() / 3);

	// Hide the channel ID widgets by default. They are shown when user clicks Live TV radio button.
	check_widgets_visibility(false);
	
	


}

// Web API Ref destructor.
webapi_ref::~webapi_ref()
{

}

// This function reads the config.txt and user.txt file to get the client ID, 
// the URL to the Gracenote Media Service, and the country.
void webapi_ref::load_config_file()
{
	QFile config_file("config.txt");
	QFile user_file("user.txt");

	if(config_file.open(QIODevice::ReadOnly))
	{

		while (!config_file.atEnd())
		{
			QString line = config_file.readLine();
			line = line.trimmed();  // Strip leading and trailing whitespace.
			line.replace("\t", " ");  // Replace tabs with spaces.
			QStringList line_strings = line.split(" ", QString::SkipEmptyParts); // Split on spaces. Consecutive spaces do not create extra fields.

			if ( (line_strings.count() != 2) ||  (line_strings[0][0]=='#'))
			{
				continue;
			}

			
			if (line_strings[0] == "client_id")
			{
				client_id = line_strings[1];
			}
			else if (line_strings[0] == "service_url")
			{
				service_url = line_strings[1];
			}
			else if (line_strings[0] == "country")
			{
				country =  line_strings[1];
			} 
			else if (line_strings[0] == "lang")
			{
				lang =  line_strings[1];
			} 
			else if (line_strings[0] == "proxy_url")
			{
				proxy_url =  line_strings[1];
			}
			else if (line_strings[0] == "proxy_port")
			{
				proxy_port =  line_strings[1];
			}
			else if (line_strings[0] == "proxy_user")
			{
				proxy_user =  line_strings[1];
			}
			else if (line_strings[0] == "proxy_passwd")
			{
				proxy_passwd =  line_strings[1];
			}
			else
			{
				QMessageBox::information( this, "Error", "Unknown config.txt file option." + line_strings[0]);
			}
		}
		config_file.close();
	}
	else
	{
		QMessageBox::information(this, "Error", "Could not open config.txt.");
	}


	if (user_file.open(QIODevice::ReadOnly))
	{
		char user_char[100];
		user_file.readLine(user_char, sizeof(user_char));
		user_file.close();
		
		if (strlen(user_char) > 0)
		{
			user_id = QString::fromUtf8(user_char).trimmed();
		}
	}

}

// This function toggles the UI to show the TV channel ID text box if Live TV is selected.
// This function also changes the GN_ID label to URL when appropriate
void webapi_ref::check_widgets_visibility( bool state)
{

	//TV channel_id box for search groupbox:
	if (ui.search_live_tv_radioButton->isChecked())
	{
		ui.tvchannelid_label->show();
		ui.tvchannelid_lineEdit->show();
	}
	else
	{
		ui.tvchannelid_label->hide();
		ui.tvchannelid_lineEdit->hide();
	}


	//select GN_ID or URL label for  FETCH groupbox
	if (ui.radiobutton_tv_batch_url->isChecked())
	{
		ui.label_gn_id_or_url->setText("URL:");
	}	
	else if (ui.radiobutton_tv_batch_update->isChecked())
	{
		ui.label_gn_id_or_url->setText("TVCHANNEL GN_ID:");
	}
	else if (ui.radiobutton_tv_channel->isChecked())
	{
		ui.label_gn_id_or_url->setText("TVPROVIDER GN_ID:");
	}
	else if (ui.radiobutton_tv_grid->isChecked())
	{
		ui.label_gn_id_or_url->setText("TVCHANNEL GN_IDS:");
	}
	else
	{
		ui.label_gn_id_or_url->setText("GN_ID:");
	}

	//display start/end dates
	if (ui.radiobutton_tv_grid->isChecked() || ui.search_live_tv_radioButton->isChecked() )
	{
		ui.label_start_date->show();
		ui.lineEdit_start_date->show();
		ui.label_end_date->show();
		ui.lineEdit_end_date->show();
	}
	else
	{
		ui.label_start_date->hide();
		ui.lineEdit_start_date->hide();
		ui.label_end_date->hide();
		ui.lineEdit_end_date->hide();
	}


	//options for tv grid
	if (ui.radiobutton_tv_grid->isChecked())
	{
		ui.label_tvgrid_mode->show();
		ui.radio_tvgrid_mode_none->show();
		ui.radio_tvgrid_mode_av_work->show();
		ui.radio_tvgrid_mode_contributor->show();
		ui.radio_tvgrid_mode_series->show();
		ui.radio_tvgrid_mode_season->show();
	}
	else
	{
		ui.label_tvgrid_mode->hide();
		ui.radio_tvgrid_mode_none->hide();
		ui.radio_tvgrid_mode_av_work->hide();
		ui.radio_tvgrid_mode_contributor->hide();
		ui.radio_tvgrid_mode_series->hide();
		ui.radio_tvgrid_mode_season->hide();
	}

	if ( (ui.radiobutton_tv_grid->isChecked() && !ui.radio_tvgrid_mode_none->isChecked()))
	{
		ui.label_option_gn_id->show();
		ui.lineEdit_option_gn_id->show();
	}
	else
	{
		ui.label_option_gn_id->hide();
		ui.lineEdit_option_gn_id->hide();
	}
	
}

// This function refreshes the UI based on the prior item in the history when the
// user clicks the Back button.
void webapi_ref::go_back()
{
	if(lookup_history.size() > 1)
	{
		lookup_history.removeFirst();

		// Update the UI.
		render();
	}
}

// This function changes the font size in tree view.
void webapi_ref::tree_set_font()
{
	QFont font = QFontDialog::getFont(0, ui.result_treeWidget->font());

	ui.result_treeWidget->setFont(font);
	ui.request_xml_plainTextEdit->setFont(font);
	ui.response_xml_plainTextEdit->setFont(font);
}

// This is a recursive helper function called by expand_tree_level(). It expands
// the tree to the correct level.
static void _expand_item(
	QTreeWidgetItem *item,
	int level
	)
{
	int i = 0;
	int child_count = 0;

	if((item == NULL) || (level <= 0))
	{
		// End of recursion.
		return;
	}

	child_count = item->childCount();

	for(i = 0; i < child_count; i++)
	{
		item->setExpanded(true);
		_expand_item(item->child(i), level - 1);
	}
}

// This function expands the tree view to the selected level. If the level
// is -1 then it shows the entire tree. It calls the recursive helper
// function "_expand_item()".
void webapi_ref::expand_tree_level()
{
	int current_level = 0;
	int top_level_item_count = 0;
	int i = 0;
	QTreeWidgetItem *item = NULL;

	QApplication::setOverrideCursor(Qt::WaitCursor);

	top_level_item_count = ui.result_treeWidget->topLevelItemCount();

	current_level = ui.level_expand_spinBox->value();
	if(current_level == -1)
	{
		ui.result_treeWidget->expandAll();
	}
	else if(current_level == 0)
	{
		ui.result_treeWidget->collapseAll();
	}
	else
	{
		ui.result_treeWidget->collapseAll();
		for(i = 0; i < top_level_item_count; i++)
		{
			item = ui.result_treeWidget->topLevelItem(i);
			_expand_item(item, current_level);
		}
	}

	QApplication::restoreOverrideCursor();
}

// This function adds the item that was just looked up to the history stack so it
// can be reloaded if the user clicks the Back button.
void webapi_ref::push_new_lookup_history(
	history_element_t new_history_elt
	)
{
	lookup_history.prepend(new_history_elt);

	// Only keep track of the last 10 elements.
	if(lookup_history.size() >= 10)
	{
		lookup_history.removeLast();
	}
}

// This function adds the list of extended query options to the XML request structure.
// These options come from the check boxes in the UI. See the Gracenote Web API documentation
// for information about these options.
QString webapi_ref::generate_select_extended()
{
	int i;

	QString select_extended = "";

	QList<QAbstractButton*> checkBoxes = ui.select_extended_options->buttons();

	for (i=0;i<checkBoxes.count();i++)
	{
		QCheckBox* box = dynamic_cast<QCheckBox*>(  checkBoxes[i]);
		if (box && box->isChecked())
		{

			if (!select_extended.isEmpty())
			{
				select_extended +=",";
			}
			select_extended += box->text();

		}
	}

	return select_extended;
}

// This function does a text search of the text entered into the search field.
// by the user. Different types of search requests are created depending on which
// search type radio button the user clicks.
void webapi_ref::text_search()
{
	webapi_error_t         error = WEBAPI_OK;
	webapi_object_t        *wobj = NULL;		// The object retrieved from the Gracenote Media Service.
	webapi_query_options_t options;				// The query options.
	QString                xml;
	history_element_t      new_history_elt;

	QApplication::setOverrideCursor(Qt::WaitCursor);

	options.range_start = ui.range_start_spinBox->value();  
	options.range_end =   ui.range_end_spinBox->value();

	if (options.range_start < 1)
	{
		options.range_start = 1;
	}

	if (options.range_end < options.range_start)
	{
		// If the range is invalid, set it to something valid.
		options.range_end = options.range_start + 19;
		ui.range_end_spinBox->setValue( options.range_end);
	}

	options.select_extended = generate_select_extended();

	if (ui.checkBox_single_best->isChecked())
	{
		options.mode = WEBAPI_XML_SINGLE_BEST;
	}

	// Search for a cast or crew member by name.
	if (ui.search_cast_radioButton->isChecked())
	{
		error = webapiclient.search(
			webapi_contributor,
			"NAME",
			ui.search_lineEdit->text(),
			&options,
			&wobj
			);
	}
	// Search for a film or episode by title.
	else if (ui.search_film_radioButton->isChecked())
	{
		error = webapiclient.search(
			webapi_work,
			"TITLE",
			ui.search_lineEdit->text(),
			&options,
			&wobj
			);
	}
	// Search for a series by title.
	else if (ui.search_series_radioButton->isChecked())
	{
		error = webapiclient.search(
			webapi_series,
			"TITLE",
			ui.search_lineEdit->text(),
			&options,
			&wobj
			);
	}
	// Search for a Live TV listing by title. 
	else if (ui.search_live_tv_radioButton->isChecked())
	{

		//fill in start and end dates if empty
		if (ui.lineEdit_start_date->text().isEmpty())
		{
			ui.lineEdit_start_date->setText(QDateTime::currentDateTimeUtc().addSecs(-3600).toString("yyyy-MM-ddThh:mm"));
		}

		if (ui.lineEdit_end_date->text().isEmpty())
		{
			ui.lineEdit_end_date->setText(QDateTime::currentDateTimeUtc().addSecs(3600).toString("yyyy-MM-ddThh:mm"));
		}



		if (ui.tvchannelid_lineEdit->text().trimmed().isEmpty())
		{
			QMessageBox::information(this, "Error", "Live TV search requires a TV Channel ID");
			error = WEBAPI_ERROR;
		}
		else
		{
			webapi_tv_setup_t tv_setup;		
			QStringList channels = ui.tvchannelid_lineEdit->text().split(",");
			int i;
			for (i=0;i<channels.count();i++)
			{
				QString chan_id = channels[i].trimmed();
				if (!chan_id.isEmpty())
				{
					tv_setup.enabled_channel_ids.append( chan_id);
				}
			}
			error = webapiclient.tv_search(&tv_setup, &options, ui.search_lineEdit->text(), ui.lineEdit_start_date->text(),  ui.lineEdit_end_date->text() , &wobj);
		}
	}
	else
	{
		QMessageBox::information(this, "Error", "Invalid search type.");
		error = WEBAPI_ERROR;
	}

	if (WEBAPI_OK != error)
	{
		QMessageBox::information(this, "Error", "Error performing lookup.");
	}
	else
	{
		// Add Request XML to a new history element.
		wobj->xml_request_string(&new_history_elt.request_xml);

		// Add Response XML to the history element.
		wobj->xml_response_string(&new_history_elt.response_xml);

		// Push the history element into the history list.
		push_new_lookup_history(new_history_elt);

		wobj->xml_object_release();
		
		render();
	}

	QApplication::restoreOverrideCursor();
}


// This function manages the items in the tree list.
void webapi_ref::tree_selection_changed()
{

	QTreeWidgetItem        *current_item = NULL;
	QString                id_key;
	QString                item_type;
	QString                default_request_type;
	QString				   attributes;
	QString                id = "";
	int                    i = 0;
	int					   item_i;

	
	QList<QTreeWidgetItem*> current_items = ui.result_treeWidget->selectedItems();

	if (current_items.count()==0)
	{
		// Sometimes this function is called, and nothing is selected.
		return;
	}

	for (item_i = 0; item_i < current_items.count(); item_i++)
	{

		current_item = current_items[item_i];

		if (current_item == NULL)
		{

			break;
		}

		item_type = current_item->text(0);
		attributes = current_item->text(1);


		//map the clicked-on item to the proper request

		if (item_type == "TVPROVIDER")
		{
			//When selecting a provider, we will use thse ID to do a channel lookup
			default_request_type = "TVCHANNEL_LOOKUP";
		} 
		else if (item_type == "TVCHANNEL")
		{
			//When selecting a channel, we will by-default do a grid request for that channel
			default_request_type = "TVGRID_LOOKUP";
		}
		else if (item_type != "URL")
		{
			//if we clicked on anything else expect a URL, it will be a fetch.  AV_WORK -> AV_WORK_FETCH, etc
			default_request_type = item_type + "_FETCH";
		}
		
		//If we clicked a URL, lets process it
		if(item_type == "URL")
		{	
			// Special case: If the selected item is an image link, call
			// the download image function.
			if (attributes.contains("IMAGE") || attributes.contains("COVERART") || attributes.contains("IPGCATEGORY_IMAGE"))
			{
				QApplication::setOverrideCursor(Qt::WaitCursor);
				download_image(item_type = current_item->text(2));
				QApplication::restoreOverrideCursor();
				break;
			}
			else if (attributes.contains("TVGRIDBATCH"))
			{
				id = current_item->text(2);  //Grab the URL as the ID.
				default_request_type = "TVGRIDBATCH URL"; // 'TVPROGRAMBATCH URL' is not a WEBAPI defined string
				break;
			} 

		}
		else //otherwise we should try to get a GN_ID out of what we clicked on
		{

			for (i=0;i<current_item->childCount();i++)
			{
				if (current_item->child(i)->text(0) == "GN_ID")  //look for GN_ID
				{
					if (item_i > 0)
					{
						id += ",";
					}

					id += current_item->child(i)->text(2);
					continue;
				}
			}
		}


	}
	ui.id_lineEdit->setText(id);

	// Find and set the correct radio button for our request

	QList<QAbstractButton*> radioButtons = ui.lookup_types->buttons();

	for (i=0;i<radioButtons.count();i++)
	{
		QRadioButton* button = dynamic_cast<QRadioButton*>(radioButtons[i]);
		
		if (button  && (button->text() == default_request_type))
		{
			button->setChecked(true);
			break;
		}
	}
}

// This function fetches data elements based on their GN_IDs. 
void webapi_ref::lookup_id()
{
	webapi_tv_setup_t	   tv_setup;
	webapi_query_options_t options;
	webapi_object_t *	   wobj = NULL;
	webapi_error_t		   error = WEBAPI_OK;
	QString				   id;
	QAbstractButton*	   checked_button = ui.lookup_types->checkedButton();
	QString				   item_type;
	history_element_t      new_history_elt;

	if (checked_button != NULL)
	{
		item_type = checked_button->text();
	}

	id = ui.id_lineEdit->text().trimmed();
		
	if (id.isEmpty() || item_type.isEmpty() )
	{
		return;
	}

	QApplication::setOverrideCursor(Qt::WaitCursor);

	options.select_extended = generate_select_extended();

	// Fetch a contributor.
	if (item_type == "CONTRIBUTOR_FETCH")
	{
	//	tv_setup.enabled_channel_ids.append( ui.tvchannelid_lineEdit->text().trimmed());
		options.tv_setup = &tv_setup;
		error = webapiclient.fetch( webapi_contributor,  id, &options, &wobj);
	}
	// Fetch an AV Work.
	else if (item_type == "AV_WORK_FETCH")
	{
	//	tv_setup.enabled_channel_ids.append( ui.tvchannelid_lineEdit->text().trimmed());
		options.tv_setup = &tv_setup;
		error = webapiclient.fetch( webapi_work,  id,  &options, &wobj);
	}
	// Fetch a series.
	else if (item_type == "SERIES_FETCH")
	{
		QTreeWidgetItem*	current_item = NULL;
		QTreeWidgetItem*	mediagraphy = NULL;
		QTreeWidgetItem*	contributor = NULL;
		QTreeWidgetItem*	gnid = NULL;
		QString				gnid_contributor_str;
		bool				do_contributor_series = false;

		// Special case:  What if the current response is a contributor? Offer option to 
		// limit the Series results to the current contributor, or show all Series results.
		current_item = ui.result_treeWidget->currentItem();
		if (current_item)
		{
			mediagraphy = current_item->parent();
			if (mediagraphy && (mediagraphy->text(0) == "MEDIAGRAPHY"))
			{
				contributor = mediagraphy->parent();
				if (contributor && contributor->text(0) == "CONTRIBUTOR")
				{
					int i;
					for (i=0;i<contributor->childCount();i++)
					{
						if (contributor->child(i)->text(0) == "GN_ID")
						{
							gnid_contributor_str = contributor->child(i)->text(2);
							break;
						}
					}
				}
			}
		}

		if (!gnid_contributor_str.isEmpty())
		{
			int result  = QMessageBox::question(	this,
				"Series Fetch",
				"Do you want to limit the Series results to the current contributor, or show all Series results?\n\n Yes: Show only the Seasons and Episodes of this Series for this contributor\n\nNo:Show the Seasons and Episodes for the entire Series.",
				QMessageBox::Yes | QMessageBox::No );

			if (result == QMessageBox::Yes)
			{
				do_contributor_series = true;
			}
			
		}

		if (do_contributor_series)
		{
			options.expand_series = id;
			error = webapiclient.fetch(webapi_contributor, gnid_contributor_str, &options, &wobj);

		}
		else
		{
			error = webapiclient.fetch( webapi_series,  id,  &options, &wobj);  // Fetch the series.
		}

	}
	// Fetch a season.
	else if (item_type == "SEASON_FETCH")
	{
		error = webapiclient.fetch( webapi_season,  id,  &options, &wobj);
	}
	// Fetch a video disc set.
	else if (item_type == "VIDEODISCSET_FETCH")
	{
		error = webapiclient.fetch( webapi_product,  id,  &options, &wobj);
	}
	// Fetch a TV program.
	else if (item_type == "TVPROGRAM_FETCH")
	{
		error = webapiclient.tv_get_program( NULL, webapi_tvprogram, id, &options, &wobj);
	}
	// Fetch a TV provider channels.
	else if (item_type == "TVCHANNEL_LOOKUP")
	{	
		tv_setup.enabled_provider = id;
		error = webapiclient.tv_get_channels( &tv_setup, &options, &wobj);
	}
	// Fetch a TV grid
	else if (item_type == "TVGRID_LOOKUP")
	{	
		int i;
		QStringList channels = id.split(','); //split input on a comma, so we can have multiple channels on one input line
	
		for (i=0;i<channels.count();i++)
		{
			QString one_channel = channels[i].trimmed();
			if (!one_channel.isEmpty())
			{
				tv_setup.enabled_channel_ids.append(one_channel);  //put single DVB triplet in the tv_setup structure
			}
		}

		//fill in start and end dates if empty
		if (ui.lineEdit_start_date->text().isEmpty())
		{
			ui.lineEdit_start_date->setText(QDateTime::currentDateTimeUtc().addSecs(-3600).toString("yyyy-MM-ddThh:mm"));
		}

		if (ui.lineEdit_end_date->text().isEmpty())
		{
			ui.lineEdit_end_date->setText(QDateTime::currentDateTimeUtc().addSecs(3600).toString("yyyy-MM-ddThh:mm"));
		}
		
		//fill in mode and gn_id for (work/contributor/series/season)-on-tv
		
		QRadioButton* checked_mode =  dynamic_cast<QRadioButton*>  (ui.tvgrid_modes->checkedButton());
		if (checked_mode && checked_mode != ui.radio_tvgrid_mode_none)
		{
			options.mode = checked_mode->text();
			options.input_gnid = ui.lineEdit_option_gn_id->text().trimmed();
		}	

		error = webapiclient.tv_get_grid(&tv_setup, &options, ui.lineEdit_start_date->text(),  ui.lineEdit_end_date->text(), &wobj);

	}


	// Fetch a TV channel batch list..
	else if (item_type == "TVGRIDBATCH_UPDATE")
	{
		tv_setup.enabled_channel_ids.append(id);

		//add the stamp (empty stamp is OK)
		tv_setup.enabled_channel_id_stamps.append(ui.lineEdit_channel_stamp->text().trimmed());
		
		error = webapiclient.tv_get_program_batch_list(&tv_setup, NULL, &wobj);
	}
	// Fetch a TV program batch URL.
	else if (item_type == "TVGRIDBATCH URL")
	{
		// Download a TV program batch.
		error = webapiclient.tv_get_program_batch( id,  &wobj);
		new_history_elt.request_xml = "HTTP GET: " + id;
	}
	else
	{
		QMessageBox::information(this, "ERROR", "Cannot look up " + item_type);
		error = WEBAPI_ERROR;
	}

	if (error == WEBAPI_OK)
	{
		// Add Request XML to a new history element.
		wobj->xml_request_string( &new_history_elt.request_xml);

				

		// Add Response XML to the history element.
		wobj->xml_response_string( &new_history_elt.response_xml);

		// Push the history element into the history list.
		push_new_lookup_history(new_history_elt);

		wobj->xml_object_release();

		render();
	}

	QApplication::restoreOverrideCursor();
}


// This function looks up the TV providers for the selected postal code 
// (North America) or DVB (Digital Video Broadcast) triplet (Europe). 
void webapi_ref::lookup_zip()
{
	//IPG starts here
	webapi_tv_setup_t      tv_setup;
	webapi_object_t        *wobj = NULL;
	webapi_query_options_t options;
	webapi_error_t         error = WEBAPI_OK;
	history_element_t      new_history_elt;
	QString				   trimmed_input;
	char*				   input = "";

	if (ui.lineEdit_zip_code->text().isEmpty())
	{
		return; 
	}

	options.select_extended = generate_select_extended();

	trimmed_input = ui.lineEdit_zip_code->text().trimmed();

	

	if (trimmed_input.left(3).toLower() == "dvb")
	{
	
		int i;
		QStringList dvbs = trimmed_input.split(','); //split input on a comma, so we can have multiple dvb triplets on one input line

		if (dvbs.count() == 0)
		{
			tv_setup.dvbs.append(trimmed_input);  //put single DVB triplet in the tv_setup structure
		}
		else
		{
			for (i=0;i<dvbs.count();i++)
			{
				QString one_triplet = dvbs[i].trimmed();
				if (!one_triplet.isEmpty())
				{
					tv_setup.dvbs.append(one_triplet);  //put single DVB triplet in the tv_setup structure
				}
			}
		}


		QApplication::setOverrideCursor(Qt::WaitCursor);
		error = webapiclient.tv_get_channels(&tv_setup, &options, &wobj);
		input = "dvb triplet";
	} 
	else
	{
		// Use the postal code.
		tv_setup.postalcode = trimmed_input;
		QApplication::setOverrideCursor(Qt::WaitCursor);
		error = webapiclient.tv_get_providers(&tv_setup, &options, &wobj);
		input = "postalcode";
	}


	QApplication::restoreOverrideCursor();
	
	if (WEBAPI_OK == error)
	{
		// Add Request XML to a new history elelement.
		wobj->xml_request_string(&new_history_elt.request_xml);

		// Add Response XML to the history element.
		wobj->xml_response_string(&new_history_elt.response_xml);

		// Push the history element into the history list.
		push_new_lookup_history(new_history_elt);

		wobj->xml_object_release();

		render();
	}
	else
	{
		QMessageBox::information( this, "Error", "There was an error retreiving channel information for selected "  +QString(input) + ".");
	}
	
}


// This is a recursive function to create the tree structure from
// the response XML. This function will add every element of the XML to the
// tree, and will highlight elements in yellow if they represent a piece of
// data that can be looked up or "navigated".
static int add_dom_to_tree(
	QTreeWidgetItem **item,
	QDomElement docElem
	)
{
	int              error = 0;
	int              namedNodeMap_count = 0;
	int              i = 0;
	QDomNode         domNode;
	QDomElement      subDomElement;
	QTreeWidgetItem	 *child_item = NULL;
	QDomNamedNodeMap namedNodeMap;
	QString          attribute_list = "";
	QString          tagname;

	*item = new QTreeWidgetItem();
	if(item == NULL)
	{
		return 1;
	}

	// Grab the element name and put it into the tree.
	tagname = docElem.tagName();
	(*item)->setText(0, tagname);

	if((tagname == "VIDEODISCSET")||
	   (tagname == "AV_WORK")     ||
	   (tagname == "CONTRIBUTOR") ||
	   (tagname == "PRODUCT")     ||
	   (tagname == "SERIES")      ||
	   (tagname == "SEASON")      ||
	   (tagname == "URL")         ||
	   (tagname == "TVPROGRAM")   ||
	   (tagname == "TVPROVIDER")  ||
	   (tagname == "TVCHANNEL") 
	  )
	{
		// Set selectable items to yellow.
		(*item)->setBackgroundColor(0, QColor(255, 255, 0));
	}

	// Grab all of the attributes from the element and put them into
	// the tree.
	namedNodeMap = docElem.attributes();
	namedNodeMap_count = namedNodeMap.count();
	if(namedNodeMap_count > 0)
	{
		for(i = 0; i < namedNodeMap_count; i++)
		{
			QDomNode attribute = namedNodeMap.item(i);

			attribute_list += attribute.nodeName();
			attribute_list += "=";
			attribute_list += attribute.nodeValue();

			if(i != (namedNodeMap_count - 1))
			{
				attribute_list += " | ";
			}
		}

		(*item)->setText(1, attribute_list);
	}

	domNode = docElem.firstChild();
	while(domNode.isNull() == false)
	{
		// Grab the element name and put it into the tree.
		if(domNode.nodeType() == QDomNode::TextNode)
		{
			(*item)->setText(2, domNode.nodeValue());
		}

		subDomElement = domNode.toElement(); // Try to convert the node to an element.
		if(subDomElement.isNull() == false)
		{
			error = add_dom_to_tree(&child_item, subDomElement);
			if(0 == error)
			{
				(*item)->addChild(child_item);
			}
		}

		domNode = domNode.nextSibling();
	}

	if(error != 0)
	{
		delete *item;
		*item = NULL;
	}

	return error;
}

// This function takes the XML response from the last lookup and calls the
// recursive add_dom_to_tree function to add the XML to the result tree.
void webapi_ref::response_to_treewidget()
{
	int             error = 0;
	QDomDocument    doc;
	QDomElement     docElem;
	QTreeWidgetItem *child_item = NULL;

	// Clear out the old response.
	ui.result_treeWidget->clear();

	ui.image_label->clear();

	if(lookup_history.size() == 0)
	{
		return;
	}

	doc.setContent(lookup_history[0].response_xml);

	docElem = doc.documentElement();

	error = add_dom_to_tree(&child_item, docElem);
	if(error == 0)
	{
		ui.result_treeWidget->addTopLevelItem(child_item);
		expand_tree_level();
	}
	else
	{
		QMessageBox::information( this, "Error", "There was an error constructing the tree from the result XML");
	}
}

// This function downloads the image specified in the URL parameter and
// places it into the image label in the application. Download events are
// triggered by clicking on a URL from the tree view.
void webapi_ref::download_image(
	QString url
	)
{
	QPixmap    tmp_img;
	QPixmap    sized;
	QByteArray img;
	QString    trimurl;
	int        return_code = 0;

	if(url.isEmpty() == true)
	{
		return;
	}

	trimurl = url.trimmed();
	if (trimurl.isEmpty() == true)
	{
		return;
	}

	img = webapiclient.perform_http_get(trimurl, &return_code);

	// Try to autodetect the png or jpg image.
	tmp_img.loadFromData(img);

	if(tmp_img.isNull() == true)
	{
		QMessageBox::information(this, "Error", "Problem downloading image (return code: " + QString::number(return_code)+")");	
	}
	else
	{
		sized = tmp_img.scaled(ui.image_label->size(), Qt::KeepAspectRatio);
		ui.image_label->setPixmap(sized);
	}
}

// Take elements from the current lookup history element and use them to
// populate the appropriate UI elements.
void webapi_ref::render()
{
	ui.request_xml_plainTextEdit->clear();
	ui.request_xml_plainTextEdit->appendPlainText(lookup_history[0].request_xml);

	ui.response_xml_plainTextEdit->clear();
	ui.response_xml_plainTextEdit->appendPlainText(lookup_history[0].response_xml);

	// And convert that response to the tree widget.
	response_to_treewidget();
	

}

// If the user double-clicks a selectable row in the tree, select the
// row item and perform an ID Lookup. This is the same as if the single-clicks
// the row and clicks the "ID Lookup" button.
void webapi_ref::tree_doubleclick(
	QTreeWidgetItem *item,
	int column
	)
{
	tree_selection_changed();
	lookup_id();
}



