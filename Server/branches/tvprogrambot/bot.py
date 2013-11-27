#!/usr/bin/env python26
# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="zhigang"
__date__ ="$2010-5-31 17:19:10$"
import sys
reload(sys)
sys.setdefaultencoding('utf8')
import os

import time
import signal
import simplejson as json
from multiprocessing import freeze_support
from multiprocessing import Process, Queue, active_children
from TvProgramBot.site.tvsou_api import SiteTvsouApi
from TvProgramBot.db.connection import getDb

def run_task(qu):
    for q in iter(qu.get, 'STOP'):
        code = q['code']
        config = q['config']
        type = q['type']
        province = q['province']
        city = q['city']
        s = SiteTvsouApi(code, config, type, province, city)
        s.run()

def main():
    run_queue = Queue(50)
    
    processes = [Process(target=run_task, args=(run_queue,))
                     for i in range(20)]
    for p in processes:
        p.daemon = True
        p.start()
    
    signal.signal(signal.SIGTERM, SignalTERM)

    db = getDb()
    channel_list = db.query('SELECT * FROM channel where autosyn=1 ORDER BY id').fetchall()
    channel_len = len(channel_list)
    i = 1
    for channel in channel_list:
        while run_queue.full():
            # 若任务队列已满，则等待
            time.sleep(3)
        config = channel['config']
        try:
            config = json.loads(config)
            run_queue.put({'code': channel['code'], 'config': config, 'type': channel['type'], 'province': channel['province'], 'city': channel['city']})
            print 'Queue: %s/%s' % (i, channel_len)
            i = i + 1
        except:
            print 'error'
            print config

    for i in range(20):
        run_queue.put("STOP")
    for p in processes:
        p.join()

def SignalTERM(sig, id):
    """ 退出信号处理
    """
    try:
        for p in active_children():
            p.terminate()
    except:
        pass
    sys.exit()

def xnix_daemon():
    # do the UNIX double-fork magic, see Stevens' "Advanced
    # Programming in the UNIX Environment" for details (ISBN 0201563177)
    try:
        pid = os.fork()
        if pid > 0:
            # exit first parent
            sys.exit(0)
    except OSError, e:
        print >>sys.stderr, "fork #1 failed: %d (%s)" % (e.errno, e.strerror)
        sys.exit(1)
    # decouple from parent environment
    os.chdir("/")
    os.setsid()
    os.umask(0)
    # do second fork
    try:
        pid = os.fork()
        if pid > 0:
            # exit from second parent, print eventual PID before
            pid_file = config.get_pidfile()
            fp = open(pid_file, 'w')
            fp.write("%s" % pid)
            fp.close()
            sys.exit(0)
    except OSError, e:
        print >>sys.stderr, "fork #2 failed: %d (%s)" % (e.errno, e.strerror)
        sys.exit(1)
    # start the daemon main loop
    main()

if __name__ == "__main__":
    print "start"
    freeze_support()
    
    main()
