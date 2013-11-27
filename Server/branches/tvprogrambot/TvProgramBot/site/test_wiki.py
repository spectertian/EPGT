
from TvProgramBot.db.connection import getDb
from TvProgramBot.db.connectionMongo import getMongo
from TvProgramBot.db.connectionMemcache import getCache
from pymongo.objectid import ObjectId


def test_wiki():
    mc = getCache()
    wikis = mc.get("wiki_liverecommend")
    
    if not wikis :
        wikis = []
        mongo_conn = getMongo()
        mongo_db = mongo_conn.epg
        wiki_liverecommend = mongo_db.wiki_liverecommend
        wiki_list = wiki_liverecommend.find().limit(100)
        for wiki in wiki_list:
            wikis.append(wiki['wiki_id'])
    if wikis:
        mc.set("wiki_liverecommend",wikis)
        return wikis
    else:
        return False

if __name__ == "__main__":
    print "start"
    main()