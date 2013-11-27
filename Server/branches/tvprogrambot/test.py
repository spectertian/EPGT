#!/usr/bin/env python26
# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="zhigang1"
__date__ ="$2010-5-31 17:19:10$"

from TvProgramBot.request import request
from TvProgramBot.db.connection import getDb
from pymongo import Connection
from pymongo.objectid import ObjectId

mongo_conn = Connection("mongodb://192.168.10.71:27017")
mongo_db = mongo_conn.epg
mongo_editmemory = mongo_db.editor_memory

editmemory = mongo_editmemory.find_one({"program_id": "4f7e79b7e3f70f78ef000407"})

if editmemory:
    print editmemory['program_id']
else:
    print 'no find'    