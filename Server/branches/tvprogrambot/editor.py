#!/usr/bin/env python26
# -*- coding: utf-8 -*-

from datetime import datetime
from datetime import timedelta
import time
from TvProgramBot.request import request
from TvProgramBot.db.connection import getDb
from TvProgramBot.db.connectionMongo import getMongo
from TvProgramBot.db.connectionMemcache import getCache
from TvProgramBot.db.skipWikis import skipWikis
from pymongo.objectid import ObjectId
from TvProgramBot.db.myStringFilter import getFilterTitle
from TvProgramBot.db.skipWikis import skipWikis


def main():
    mongo_conn = getMongo()
    mongo_db = mongo_conn.epg
    mongo_editmemory = mongo_db.editor_memory
    mongo_editmemory1 = mongo_db.editor_memory1
    
    
    t_str = '2012-03-22 00:00:00'
    sd = datetime.strptime(t_str, '%Y-%m-%d %H:%M:%S')
    nd = datetime.now()
    dd = (nd - sd).days
    dd = dd + 1
    mm = ''
        
    for i in range(0, dd):
        day = sd + timedelta(days=i)
        das = day + timedelta(days=1)

        query = {"created_at":{u'$gt' : day,r'$lt':das}}
        edits = mongo_editmemory.find(query)
        for edit in edits:
            program_name = edit['program_name'].encode('utf-8')
            newname,mm = getFilterTitle(program_name)
            channel_code = edit['channel_code']
            wiki_id = edit['wiki_id']
            created_at = edit['created_at']
            
            print channel_code,"\n",program_name,"\n",newname,"\n",created_at,"\n","\n"
            
            rows = mongo_editmemory1.find_one({"channel_code" : channel_code, "program_name" : newname})
            if rows:
                print "---------"
                rows['channel_code']
            else:
                print "+++++++++"
                em1 = []
                p = {}
                p['channel_code'] = channel_code
                p['program_name'] = newname
                p['wiki_id'] = wiki_id
                p['created_at'] = created_at
                em1.append(p)
                mongo_editmemory1.insert(em1)
        

if __name__ == "__main__":
    main()