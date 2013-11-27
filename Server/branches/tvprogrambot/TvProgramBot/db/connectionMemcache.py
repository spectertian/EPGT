# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

import memcache


def getCache():
    return memcache.Client(['127.0.0.1:11211'],debug=0)

