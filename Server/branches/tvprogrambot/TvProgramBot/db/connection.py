# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.


from TvProgramBot.db.mysql import MySql


def getDb():
    return MySql('192.168.6.68', 'epg', 'epgpass', 'epg')
