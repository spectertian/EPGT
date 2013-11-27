# -*- coding: utf-8 -*-
'''
Created on Oct 27, 2009

@author: sxin
'''

import MySQLdb
from MySQLdb.cursors import DictCursor
#import MySQLdb.cursors

class MySql:
    _conn = ''

    def __init__(self, host, user, password, db, port=3306):
        i = host.find(':')
        if i >= 0:
            host, port = host[:i], int(host[i+1:])
        else:
            host, port = host, port
        self._conn = MySQLdb.connect(user=user,
                                     passwd=password,
                                     db=db,
                                     charset='utf8',
                                     host=host,
                                     port=port,
                                     cursorclass=DictCursor,
                                     connect_timeout=10)

    def query(self, sql, params=None):
        try:
            c = self._conn.cursor()
            c.execute(sql, params)
        except:
            raise
        return c

    def commit(self):
        """ 提交事务 """
        self.query('COMMIT')
