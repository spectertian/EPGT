# -*- coding: utf-8 -*-

try:
    import pycurl222
    from cStringIO import StringIO
    curl = True
except Exception, e:
    import urllib2
    curl = False

def request(url):
    """蜘蛛抓取"""
    if curl:
        return _curlRequest(url)
    else:
        return _urllibRequest(url)

def _curlRequest(url):
    """curl下载函数"""
    c = pycurl.Curl()
    c.setopt(pycurl.URL, url)

    sio = StringIO()
    c.setopt(pycurl.WRITEFUNCTION, sio.write)
    c.setopt(pycurl.FOLLOWLOCATION, 1)
    c.setopt(pycurl.MAXREDIRS, 5)
    # c.setopt(pycurl.ENCODING, '')
    c.setopt(pycurl.USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.7) Gecko/2009021910 Firefox/3.0.7')
    # c.setopt(pycurl.PROXY, '192.168.0.138:8888')
    # c.setopt(pycurl.PROXY, '127.0.0.1:9050')
    # c.setopt(pycurl.PROXYTYPE, pycurl.PROXYTYPE_SOCKS5)
    c.perform()
    c.close()
    return sio.getvalue()

def _urllibRequest(url):
    """urllib下载函数"""
    req = urllib2.Request(url)
    req.add_header('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.7) Gecko/2009021910 Firefox/3.0.7')
    return urllib2.build_opener().open(req).read()

