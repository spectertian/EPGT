# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

from pymongo import Connection


def getMongo():
    return Connection("mongodb://192.168.6.68:27017")
