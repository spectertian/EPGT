# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

""" 搜视网(http://www.tvsou.com/)抓取分析蜘蛛【暂停使用】

此站包括国内大部分电视节目，内容较全，但仅每周一公布本周电视节目。
"""
__author__="zhigang"
__date__ ="$2010-5-31 17:51:21$"

import sys
import string
from cStringIO import StringIO
import re
import calendar

import BeautifulSoup
from TvProgramBot.site.base import SiteBase

import Image

#import chardet

tvsou_url = {
    'cctv1': string.Template('http://epg.tvsou.com/programys/tv_1/channel_1/W${week}.htm'),
    'cctv2': string.Template('http://epg.tvsou.com/programys/tv_1/channel_3/W${week}.htm'),
    'cctv3': string.Template('http://epg.tvsou.com/programys/tv_1/channel_4/W${week}.htm'),
    'cctv4_asia': string.Template('http://epg.tvsou.com/programys/tv_1/channel_5/W${week}.htm'),
    'cctv4_europe': string.Template('http://epg.tvsou.com/programys/tv_1/channel_1080/W${week}.htm'),
    'cctv4_america': string.Template('http://epg.tvsou.com/programys/tv_1/channel_1081/W${week}.htm'),
    'cctv5': string.Template('http://epg.tvsou.com/programys/tv_1/channel_6/W${week}.htm'),
    'cctv6': string.Template('http://epg.tvsou.com/programys/tv_1/channel_7/W${week}.htm'),
    'cctv7': string.Template('http://epg.tvsou.com/programys/tv_1/channel_8/W${week}.htm'),
    'cctv8': string.Template('http://epg.tvsou.com/programys/tv_1/channel_9/W${week}.htm'),
    'cctv9': string.Template('http://epg.tvsou.com/programys/tv_1/channel_10/W${week}.htm'),
    'cctv10': string.Template('http://epg.tvsou.com/programys/tv_1/channel_11/W${week}.htm'),
    'cctv11': string.Template('http://epg.tvsou.com/programys/tv_1/channel_12/W${week}.htm'),
    'cctv12': string.Template('http://epg.tvsou.com/programys/tv_1/channel_13/W${week}.htm'),
    'cctv_news': string.Template('http://epg.tvsou.com/programys/tv_1/channel_14/W${week}.htm'), # 央视 新闻频道
    'cctv_kids': string.Template('http://epg.tvsou.com/programys/tv_1/channel_15/W${week}.htm'), # 央视 少儿频道
    'cctv_music': string.Template('http://epg.tvsou.com/programys/tv_1/channel_16/W${week}.htm'), # 央视 音乐频道

    'antv': string.Template('http://epg.tvsou.com/program/tv_20/channel_42/w${week}.htm'),  # 安徽卫视
    'e08e2ceb9d2e26639fcc3d8714f65976': string.Template('http://epg.tvsou.com/program/tv_20/channel_188/w${week}.htm'),  # 安徽电视科教频道
    '055cbd5fcab4232b6f1f625b7718c031': string.Template('http://epg.tvsou.com/program/tv_20/channel_189/w${week}.htm'),  # 安徽经济生活频道
    'a233c7e03c20f9c3edce8cdf65f3fedd': string.Template('http://epg.tvsou.com/program/tv_20/channel_343/w${week}.htm'),  # 安徽影视频道
    '4f402381fd3821c37351d22b2e65527a': string.Template('http://epg.tvsou.com/program/tv_20/channel_344/w${week}.htm'),  # 安徽文体频道
    '808b19f5d37e25ab32d86ff2fa38a61f': string.Template('http://epg.tvsou.com/program/tv_20/channel_345/w${week}.htm'),  # 安徽公共频道
    'dc552da6c68c2973b9c5d53488d5764b': string.Template('http://epg.tvsou.com/program/tv_78/channel_346/w${week}.htm'),  # 合肥新闻频道
    '293d3d46e9deb8b58f511deb960a5515': string.Template('http://epg.tvsou.com/program/tv_78/channel_347/w${week}.htm'),  # 合肥生活频道
    'af9956206589515158ee340e6560b01b': string.Template('http://epg.tvsou.com/program/tv_78/channel_348/w${week}.htm'),  # 合肥法制频道
    '4c387c9a31963e7f5f0f97b24f3bfb85': string.Template('http://epg.tvsou.com/program/tv_78/channel_350/w${week}.htm'),  # 合肥财经频道
    '055359cff936b6ba3d28653eaed6ed52': string.Template('http://epg.tvsou.com/program/tv_78/channel_1075/w${week}.htm'),  # 合肥第一剧场频道
    '86d8484ee294b0c3cee71c10af073647': string.Template('http://epg.tvsou.com/program/tv_78/channel_645/w${week}.htm'),  # 合肥家庭影院频道
    'dca77640b9ad9814ac135d82e34d567a': string.Template('http://epg.tvsou.com/program/tv_78/channel_676/w${week}.htm'),  # 肥西台
    'c112fcfbbc96f8b359f272bf0112c51f': string.Template('http://epg.tvsou.com/program/tv_78/channel_677/w${week}.htm'),  # 肥东台
    '830d515faaa1719d6027f515708bd6a2': string.Template('http://epg.tvsou.com/program/tv_78/channel_678/w${week}.htm'),  # 长丰台
    'beeceaba345e18ca6841c8d819a94304': string.Template('http://epg.tvsou.com/program/tv_192/channel_835/w${week}.htm'),  # 蚌埠一套
    '489397c2c32a8e41b3bb889ddc7e25d4': string.Template('http://epg.tvsou.com/program/tv_192/channel_836/w${week}.htm'),  # 蚌埠二套
    '6bdcbd2ae653e0cf4c282cfde39412af': string.Template('http://epg.tvsou.com/program/tv_192/channel_837/w${week}.htm'),  # 蚌埠三套
    'bcbebe42c4f7146ed4df427f577fc805': string.Template('http://epg.tvsou.com/program/tv_195/channel_849/w${week}.htm'),  # 淮南一套
    '2450fe3bb4313c2eecf34c3b4f1c4dcf': string.Template('http://epg.tvsou.com/program/tv_195/channel_850/w${week}.htm'),  # 淮南二套
    '800af11a321bc4076b59dd23d14f7720': string.Template('http://epg.tvsou.com/program/tv_195/channel_851/w${week}.htm'),  # 淮南影视频道
    '5dfcaefe6e7203df9fbe61ffd64ed1c4': string.Template('http://epg.tvsou.com/program/tv_38/channel_60/w${week}.htm'),  # 北京电视台-1
    '6a3f44b1abfdfb49ddd051f9e683c86d': string.Template('http://epg.tvsou.com/program/tv_38/channel_61/w${week}.htm'),  # 北京电视台-2
    '7f66a261de12a988379cf569b2adb9cb': string.Template('http://epg.tvsou.com/program/tv_38/channel_62/w${week}.htm'),  # 北京电视台-3
    '69f1c76911f03d7687b713b9f59ede4e': string.Template('http://epg.tvsou.com/program/tv_38/channel_63/w${week}.htm'),  # 北京电视台-4
    'e1dc01e75b3d1c80bc5c5764e8619790': string.Template('http://epg.tvsou.com/program/tv_38/channel_64/w${week}.htm'),  # 北京电视台-5
    '23ab87816c24f90e5865116512e12c47': string.Template('http://epg.tvsou.com/program/tv_38/channel_65/w${week}.htm'),  # 北京电视台-6
    '995ebb2ed36697b4421bc24daeddba93': string.Template('http://epg.tvsou.com/program/tv_38/channel_66/w${week}.htm'),  # 北京电视台-7
    '3009e6f002f4608e015a65e71b3ea5d6': string.Template('http://epg.tvsou.com/program/tv_38/channel_67/w${week}.htm'),  # 北京电视台-8
    '973de2c3a14d5f888241c685a1f0f618': string.Template('http://epg.tvsou.com/program/tv_38/channel_68/w${week}.htm'),  # 北京电视台-9
    '4ca4b98c6ada2c7f649b5149d39d8816': string.Template('http://epg.tvsou.com/program/tv_38/channel_69/w${week}.htm'),  # 北京电视台-10
    'fcc4eabadaf03c98f7e61018e97c6d03': string.Template('http://epg.tvsou.com/program/tv_44/channel_18/w${week}.htm'),  # 中国教育台-1
    'eecd24c6ed3d9efd5c85c3f885cb8afb': string.Template('http://epg.tvsou.com/program/tv_44/channel_19/w${week}.htm'),  # 中国教育台-2
    '37fc43f6d3045011137fa5ffa6b35104': string.Template('http://epg.tvsou.com/program/tv_44/channel_20/w${week}.htm'),  # 中国教育台-3
    'ccde5c5d525542675a7a50c02b4c9778': string.Template('http://epg.tvsou.com/program/tv_44/channel_21/w${week}.htm'),  # CETV空中课堂
    '5731a167d79c432575056c4963dc8049': string.Template('http://epg.tvsou.com/program/tv_37/channel_59/w${week}.htm'),  # 重庆卫视
    '0e4f095c9512db564cc34edec418cae5': string.Template('http://epg.tvsou.com/program/tv_37/channel_199/w${week}.htm'),  # 重庆影视
    '10ef9ae91bf49145facef403398341e9': string.Template('http://epg.tvsou.com/program/tv_37/channel_200/w${week}.htm'),  # 重庆新闻
    'e6a2d4a82e7cc61001140b29100b3c06': string.Template('http://epg.tvsou.com/program/tv_37/channel_201/w${week}.htm'),  # 重庆科教
    '473c3b207aefd3fc46eb8af941e73baa': string.Template('http://epg.tvsou.com/program/tv_37/channel_202/w${week}.htm'),  # 重庆都市
    '323ddcd06894e405e94fb3b08b720896': string.Template('http://epg.tvsou.com/program/tv_37/channel_203/w${week}.htm'),  # 重庆娱乐
    '074455d6d13c896c9db61a6b4c42133e': string.Template('http://epg.tvsou.com/program/tv_37/channel_204/w${week}.htm'),  # 重庆生活
    '08568c12ac358e5f7084acc2cfd42c1c': string.Template('http://epg.tvsou.com/program/tv_37/channel_205/w${week}.htm'),  # 重庆时尚
    'cde824539dbce1fb58f0745dbe4321fc': string.Template('http://epg.tvsou.com/program/tv_37/channel_206/w${week}.htm'),  # 重庆公共
    '4626448d4132c36fa359294a33e7c65f': string.Template('http://epg.tvsou.com/program/tv_37/channel_998/w${week}.htm'),  # 重庆青少频道
    'fjtv': string.Template('http://epg.tvsou.com/program/tv_29/channel_51/w${week}.htm'),  # 东南(福建)卫视
    '9bf061918e03b5b0a16b9c64967d1183': string.Template('http://epg.tvsou.com/program/tv_29/channel_307/w${week}.htm'),  # 福建综合频道
    'edc3febdf1a3732fe5ec2f9dc2da8a8c': string.Template('http://epg.tvsou.com/program/tv_29/channel_308/w${week}.htm'),  # 福建公共频道
    'e9c85fe17ad0a5a17857dcfa133bcfd2': string.Template('http://epg.tvsou.com/program/tv_29/channel_309/w${week}.htm'),  # 福建新闻频道
    '384e29a443724c2f2bac72b97ed84593': string.Template('http://epg.tvsou.com/program/tv_29/channel_310/w${week}.htm'),  # 福建电视剧频道
    '2e0457e4b64b207028f96bb445dc271a': string.Template('http://epg.tvsou.com/program/tv_29/channel_311/w${week}.htm'),  # 福建都市时尚频道
    '65b3fe20affc5fc44c35eafd9c675cec': string.Template('http://epg.tvsou.com/program/tv_29/channel_312/w${week}.htm'),  # 福建经济生活频道
    '48f73b99932cd50cbff2db6e96c52e56': string.Template('http://epg.tvsou.com/program/tv_29/channel_313/w${week}.htm'),  # 福建体育频道
    'd1693ad1f85fbbde980068f3aaca7f0f': string.Template('http://epg.tvsou.com/program/tv_29/channel_1005/w${week}.htm'),  # 福建少儿频道
    '19ea1ebfe5388d874d07d7a9075b3ff8': string.Template('http://epg.tvsou.com/program/tv_29/channel_1006/w${week}.htm'),  # 福建教育台
    '52cab2da06836769cad3cafcd109442a': string.Template('http://epg.tvsou.com/program/tv_77/channel_333/w${week}.htm'),  # 厦门新闻频道
    '432847d3bcb0aac6b28be1e6311aa629': string.Template('http://epg.tvsou.com/program/tv_77/channel_334/w${week}.htm'),  # 厦门纪实频道
    '35af15b34cfc4bfcb391fcacd3005497': string.Template('http://epg.tvsou.com/program/tv_77/channel_335/w${week}.htm'),  # 厦门生活频道
    '78009cfe0ced5ac8686021cac98e8e04': string.Template('http://epg.tvsou.com/program/tv_77/channel_336/w${week}.htm'),  # 厦门影视频道
    '6519e05b08f08a507a3bd4ea71df8053': string.Template('http://epg.tvsou.com/program/tv_77/channel_337/w${week}.htm'),  # 厦门卫视
    '77a8a73d062da3ab787e9a0daeffa1ee': string.Template('http://epg.tvsou.com/program/tv_87/channel_477/w${week}.htm'),  # 福州综合频道
    'a3442f8e125a8487b362b1e837933dd1': string.Template('http://epg.tvsou.com/program/tv_87/channel_478/w${week}.htm'),  # 福州影视频道
    '8b96de917a18e8eb2358b70952473159': string.Template('http://epg.tvsou.com/program/tv_87/channel_479/w${week}.htm'),  # 福州生活频道
    '8b380aff64449e1d5e76dc05290140d1': string.Template('http://epg.tvsou.com/program/tv_129/channel_667/w${week}.htm'),  # 泉州新闻综合频道
    'eec11deb88c56431158bd519bd27c253': string.Template('http://epg.tvsou.com/program/tv_129/channel_668/w${week}.htm'),  # 泉州都市生活频道
    'c89fd20c507a1c36af90dd916f111f03': string.Template('http://epg.tvsou.com/program/tv_129/channel_669/w${week}.htm'),  # 泉州影视剧频道
    'e40cadcbaf4e8d47c5dfd1002df68504': string.Template('http://epg.tvsou.com/program/tv_155/channel_741/w${week}.htm'),  # 漳州一套
    '49c37a570da6aef894cd624e8e59ce86': string.Template('http://epg.tvsou.com/program/tv_155/channel_742/w${week}.htm'),  # 漳州二套
    '99fa43f6edd708ac200745239b30a7a7': string.Template('http://epg.tvsou.com/program/tv_171/channel_794/w${week}.htm'),  # 龙岩台
    'd6351c4be1b49936404656d7514955f9': string.Template('http://epg.tvsou.com/program/tv_173/channel_795/w${week}.htm'),  # 莆田台
    '8da6a5123ac504d688af818ba45d5136': string.Template('http://epg.tvsou.com/program/tv_174/channel_798/w${week}.htm'),  # 三明台
    'bf5890a2b27669536d737a41c38cefd7': string.Template('http://epg.tvsou.com/program/tv_175/channel_797/w${week}.htm'),  # 南平台
    '5ace8ddc54a4151bbcf76e56c8aa582a': string.Template('http://epg.tvsou.com/program/tv_30/channel_52/w${week}.htm'),  # 甘肃卫视
    '021af3248f5b47fe1ee36507aa02ce71': string.Template('http://epg.tvsou.com/program/tv_30/channel_537/w${week}.htm'),  # 甘肃经济频道
    'c54f8b4c7eb04761ec4149df4869840a': string.Template('http://epg.tvsou.com/program/tv_30/channel_538/w${week}.htm'),  # 甘肃文化少儿频道
    'a30d5e587154e7c0fe48acb9dd6c79ce': string.Template('http://epg.tvsou.com/program/tv_30/channel_539/w${week}.htm'),  # 甘肃公共频道
    '48bd75549a964d7b2e18802f51c55be1': string.Template('http://epg.tvsou.com/program/tv_30/channel_540/w${week}.htm'),  # 甘肃都市频道
    'ca9ad96ad863b0b7b6c51951bb726c94': string.Template('http://epg.tvsou.com/program/tv_30/channel_541/w${week}.htm'),  # 甘肃影视频道
    'f0e835b38ffc3bd0c18e7c676f0d5c90': string.Template('http://epg.tvsou.com/program/tv_123/channel_639/w${week}.htm'),  # 兰州新闻综合频道
    'ef649c56786e79fbe1c2fc9818f0678c': string.Template('http://epg.tvsou.com/program/tv_123/channel_640/w${week}.htm'),  # 兰州综艺体育频道
    '45a411978756558b95a6ead08fb7f625': string.Template('http://epg.tvsou.com/program/tv_123/channel_641/w${week}.htm'),  # 兰州生活经济频道
    'a0c376d17b894ba1ab32d13eef5c96f4': string.Template('http://epg.tvsou.com/program/tv_123/channel_642/w${week}.htm'),  # 兰州公共频道
    'cacbf9bd4a96c8dab9c6af54f59e1ae5': string.Template('http://epg.tvsou.com/program/tv_143/channel_723/w${week}.htm'),  # 天水新闻综合频道
    '539cd916acb7b3de4410d4e767a1c64d': string.Template('http://epg.tvsou.com/program/tv_143/channel_724/w${week}.htm'),  # 天水公共频道
    '378d03122e8b1fdf02f14cc0bb47923d': string.Template('http://epg.tvsou.com/program/tv_189/channel_826/w${week}.htm'),  # 金昌视频频道
    'f124ccbf87073930aa2803ffebf037ed': string.Template('http://epg.tvsou.com/program/tv_189/channel_827/w${week}.htm'),  # 金昌综合频道
    'c8bf387b1824053bdb0423ef806a2227': string.Template('http://epg.tvsou.com/program/tv_39/channel_70/w${week}.htm'),  # 广东卫视
    'cc97dfcddf7fcaa86e15456cd88c2676': string.Template('http://epg.tvsou.com/program/tv_39/channel_71/w${week}.htm'),  # 广东珠江频道
    '36c4230eafac2a4794862c04d8a1288b': string.Template('http://epg.tvsou.com/program/tv_39/channel_72/w${week}.htm'),  # 广东体育频道
    '428d42cb45c3e4ec4b842747b0d3767b': string.Template('http://epg.tvsou.com/program/tv_39/channel_73/w${week}.htm'),  # 广东公共频道
    '1bdbf8467fdda8098b8a5c28c22d5fac': string.Template('http://epg.tvsou.com/program/tv_43/channel_74/w${week}.htm'),  # 广州综合频道
    'ff953871e6ecf631453531394fd3ad60': string.Template('http://epg.tvsou.com/program/tv_43/channel_75/w${week}.htm'),  # 广州新闻频道
    'ea318de3e12a96c54314471361767ab5': string.Template('http://epg.tvsou.com/program/tv_43/channel_76/w${week}.htm'),  # 广州竞赛频道
    '9cfbe9fd82b422375a4804c3a936335f': string.Template('http://epg.tvsou.com/program/tv_43/channel_77/w${week}.htm'),  # 广州影视频道
    'd16e9e21e02d899a080e7b2cb58d5303': string.Template('http://epg.tvsou.com/program/tv_43/channel_78/w${week}.htm'),  # 广州英语频道
    '1b703fc1f2e553683a339170940680f8': string.Template('http://epg.tvsou.com/program/tv_43/channel_79/w${week}.htm'),  # 广州经济频道
    '89ae290e35e6a47e5fe3decfee5d0a1f': string.Template('http://epg.tvsou.com/program/tv_43/channel_80/w${week}.htm'),  # 广州少儿频道
    '5e2bb6c9d93e94f7386ddbbee11b06d5': string.Template('http://epg.tvsou.com/program/tv_40/channel_101/w${week}.htm'),  # 南方经济频道
    '5ab2e87ad76ac4b183c2eb678529380e': string.Template('http://epg.tvsou.com/program/tv_40/channel_102/w${week}.htm'),  # 南方卫视
    '8dfcb60ed0b558cbe36eef02764bb001': string.Template('http://epg.tvsou.com/program/tv_40/channel_103/w${week}.htm'),  # 南方综艺频道
    'd2db28eedadbf19fae266e2a3cc8ea9d': string.Template('http://epg.tvsou.com/program/tv_40/channel_104/w${week}.htm'),  # 南方影视频道
    'ef0e5fb427e242dad3ea07a9ca16fd3f': string.Template('http://epg.tvsou.com/program/tv_40/channel_105/w${week}.htm'),  # 南方少儿频道
    '20831bb807a45638cfaf81df1122024d': string.Template('http://epg.tvsou.com/program/tv_42/channel_93/w${week}.htm'),  # 深圳卫视
    'a15d0d45eb2bf83c51cb9ec942231f40': string.Template('http://epg.tvsou.com/program/tv_42/channel_94/w${week}.htm'),  # 深视都市频道
    '61e7c7d463d5d817a5d7c84cdbf05cef': string.Template('http://epg.tvsou.com/program/tv_42/channel_95/w${week}.htm'),  # 深视电视剧频道
    '41ed87645afd3db8f798126995760c13': string.Template('http://epg.tvsou.com/program/tv_42/channel_96/w${week}.htm'),  # 深视财经生活
    'ffee11d92ae3ddae5d84ecd3a70192d7': string.Template('http://epg.tvsou.com/program/tv_42/channel_97/w${week}.htm'),  # 深视娱乐频道
    '6e7b67c9fbac305f3aafb6b310066612': string.Template('http://epg.tvsou.com/program/tv_42/channel_98/w${week}.htm'),  # 深视体育健康
    '2d0843b0b352209848c97dce2b1f7d05': string.Template('http://epg.tvsou.com/program/tv_42/channel_99/w${week}.htm'),  # 深视少儿频道
    '4c40046c99b8719145ec4d33705166d7': string.Template('http://epg.tvsou.com/program/tv_42/channel_100/w${week}.htm'),  # 深视公共频道
    '476832d490e070c69f3d08ef4c2d563e': string.Template('http://epg.tvsou.com/program/tv_61/channel_227/w${week}.htm'),  # 珠海第一频道
    'cc8bbce9ac6911a75fe207dee659390d': string.Template('http://epg.tvsou.com/program/tv_61/channel_497/w${week}.htm'),  # 珠海第二频道
    '8585769970a0ec4bc24708b1eb4f2ea4': string.Template('http://epg.tvsou.com/program/tv_66/channel_238/w${week}.htm'),  # 潮州公共频道
    '5051430d2d78a7fa7f5a489d2e655329': string.Template('http://epg.tvsou.com/program/tv_66/channel_239/w${week}.htm'),  # 潮州新闻综合频道
    '22b50dd64342f5c3b0175731f753cfbe': string.Template('http://epg.tvsou.com/program/tv_94/channel_498/w${week}.htm'),  # 湛江综合频道
    '74788d71c5ea87c97bdb11723de44161': string.Template('http://epg.tvsou.com/program/tv_94/channel_499/w${week}.htm'),  # 湛江公共频道
    '6f84f87bf23b67a08459d27d6f6698f4': string.Template('http://epg.tvsou.com/program/tv_95/channel_500/w${week}.htm'),  # 佛山新闻综合频道
    '95f9c68d63768d7106fafe9b9e885b22': string.Template('http://epg.tvsou.com/program/tv_95/channel_501/w${week}.htm'),  # 佛山电视影视频道
    '29e2f1f68a9000ea53f2e7fe277e17ab': string.Template('http://epg.tvsou.com/program/tv_95/channel_502/w${week}.htm'),  # 佛山公共频道
    'c3d193686f5e9dc7914a9cc744f9fed5': string.Template('http://epg.tvsou.com/program/tv_95/channel_637/w${week}.htm'),  # 佛山电视南海频道
    '47c7e6ddf6dd4513d4b11107758d5f1e': string.Template('http://epg.tvsou.com/program/tv_95/channel_638/w${week}.htm'),  # 佛山电视顺德频道
    'c08049dcd85ab3e3f8615af79bedda42': string.Template('http://epg.tvsou.com/program/tv_96/channel_503/w${week}.htm'),  # 肇庆新闻综合频道
    'f25b05c99eb13def58bfcb3ee2652cf5': string.Template('http://epg.tvsou.com/program/tv_96/channel_504/w${week}.htm'),  # 肇庆公共频道
    'c20e6d215df5f39618c26e3382daaf1f': string.Template('http://epg.tvsou.com/program/tv_117/channel_603/w${week}.htm'),  # 东莞第一频道
    '6a580eb741d19abf68cb51050e90df0b': string.Template('http://epg.tvsou.com/program/tv_117/channel_604/w${week}.htm'),  # 东莞第二频道
    '01a454bacbba0483ec1db275b223c782': string.Template('http://epg.tvsou.com/program/tv_126/channel_659/w${week}.htm'),  # 惠州新闻综合频道
    '739473933f88a3bca8c36ccfa44d824c': string.Template('http://epg.tvsou.com/program/tv_126/channel_660/w${week}.htm'),  # 惠州经济生活频道
    '243e1b170901ce7537487aa5d6a225d5': string.Template('http://epg.tvsou.com/program/tv_130/channel_670/w${week}.htm'),  # 中山综合频道
    '28c3e3c3fb8801a5d9063f78e3d87744': string.Template('http://epg.tvsou.com/program/tv_130/channel_671/w${week}.htm'),  # 中山公共频道
    '8c8df144da19ce283e6a5c83ec8c5a05': string.Template('http://epg.tvsou.com/program/tv_138/channel_707/w${week}.htm'),  # 汕头经济生活道
    'e49ada381bb283d2ba7972c7039ad4f8': string.Template('http://epg.tvsou.com/program/tv_138/channel_708/w${week}.htm'),  # 汕头新闻综合频道
    'b6d6582b069617a9d16b542a5fff7e58': string.Template('http://epg.tvsou.com/program/tv_138/channel_709/w${week}.htm'),  # 汕头影视文艺频
    '37212f37a84da8c383e00de59a4fe678': string.Template('http://epg.tvsou.com/program/tv_162/channel_763/w${week}.htm'),  # 茂名公共频道
    'df4b5b5074faf296d101fed02a0669b1': string.Template('http://epg.tvsou.com/program/tv_162/channel_764/w${week}.htm'),  # 茂名综合频道
    '5cbb108dbf59f2ae1849ec8d1126d1a5': string.Template('http://epg.tvsou.com/program/tv_28/channel_50/w${week}.htm'),  # 广西卫视
    '8e2c0400885626844c5b0c268f075d0e': string.Template('http://epg.tvsou.com/program/tv_28/channel_174/w${week}.htm'),  # 广西综艺频道
    '9db64010730d44af442da48060b56e85': string.Template('http://epg.tvsou.com/program/tv_28/channel_175/w${week}.htm'),  # 广西都市频道
    'cf479980f626d462b7810964bf4578ec': string.Template('http://epg.tvsou.com/program/tv_28/channel_176/w${week}.htm'),  # 广西公共频道
    '448ae19caec38ea7fd87a46ffb0f10bd': string.Template('http://epg.tvsou.com/program/tv_28/channel_177/w${week}.htm'),  # 广西影视频道
    '0ab6b977da06c06998b7030145a15211': string.Template('http://epg.tvsou.com/program/tv_28/channel_178/w${week}.htm'),  # 广西体育频道
    'f8d4f343e93210041539c00b0794e382': string.Template('http://epg.tvsou.com/program/tv_58/channel_195/w${week}.htm'),  # 柳州新闻综合频道
    '108f98e86ed4bff297dd34d21069c8e4': string.Template('http://epg.tvsou.com/program/tv_58/channel_196/w${week}.htm'),  # 柳州生活科教频道
    'f6ce9653af59becfcacf4f9c0a897e87': string.Template('http://epg.tvsou.com/program/tv_58/channel_197/w${week}.htm'),  # 柳州影视公共频道
    '557c9b5499f0ad2e2a5caa002fb05735': string.Template('http://epg.tvsou.com/program/tv_73/channel_283/w${week}.htm'),  # 南宁新闻综合频道
    '7aaf01e96c0218a72cfcfd97699ce620': string.Template('http://epg.tvsou.com/program/tv_73/channel_284/w${week}.htm'),  # 南宁影视娱乐频道
    '1e51da825829fb5e6382b75f6ea8b6ec': string.Template('http://epg.tvsou.com/program/tv_73/channel_285/w${week}.htm'),  # 南宁都市生活频道
    'eb446a91fad872c0150a0edcbd9db052': string.Template('http://epg.tvsou.com/program/tv_73/channel_286/w${week}.htm'),  # 南宁公共频道
    '61539caf2042bb24c629bc8946c739cc': string.Template('http://epg.tvsou.com/program/tv_185/channel_817/w${week}.htm'),  # 玉林新闻综合频道
    '5b48d6b6cc01de776afa55e1319855ec': string.Template('http://epg.tvsou.com/program/tv_185/channel_818/w${week}.htm'),  # 玉林公共频道
    'be1b899be6b1a5e1036502070f8b6d79': string.Template('http://epg.tvsou.com/program/tv_185/channel_819/w${week}.htm'),  # 玉林知识频道
    '1aafeff0d2a08e99b97001359ed66635': string.Template('http://epg.tvsou.com/program/tv_227/channel_1024/w${week}.htm'),  # 梧州一套
    'b31f6011aa06508717f73e46d6c848a2': string.Template('http://epg.tvsou.com/program/tv_227/channel_1025/w${week}.htm'),  # 梧州二套
    '5a7d01661b5d9c64293860531374312b': string.Template('http://epg.tvsou.com/program/tv_26/channel_48/w${week}.htm'),  # 贵州卫视
    'e3b155f51dfa928af201d3f05cb26ffe': string.Template('http://epg.tvsou.com/program/tv_26/channel_376/w${week}.htm'),  # 贵州公共频道
    'b237c730155d17afddb1bef2ad05d5be': string.Template('http://epg.tvsou.com/program/tv_26/channel_377/w${week}.htm'),  # 贵州电视剧频道
    '2af3b7657f366774fa3c1bb3b43275ec': string.Template('http://epg.tvsou.com/program/tv_26/channel_378/w${week}.htm'),  # 贵州大众电影频道
    '2dd9994468bdf3ab456ff871096eadea': string.Template('http://epg.tvsou.com/program/tv_26/channel_379/w${week}.htm'),  # 贵州时尚生活频道
    '55390b8c37473b40c11717c51ee33b0d': string.Template('http://epg.tvsou.com/program/tv_26/channel_380/w${week}.htm'),  # 贵州科教健康频道
    '3d23c7fa7feae2ea2b6e3f7f1359aa7a': string.Template('http://epg.tvsou.com/program/tv_26/channel_381/w${week}.htm'),  # 贵州天元围棋频道
    '7ec3142adb7bde4ae02b11344a4e1ab5': string.Template('http://epg.tvsou.com/program/tv_26/channel_593/w${week}.htm'),  # 贵州视觉生活频道
    'f76a720dcc1be50fb16347fdcd848e24': string.Template('http://epg.tvsou.com/program/tv_81/channel_382/w${week}.htm'),  # 贵阳新闻综合频道
    '3b416101cebc0a6cdbbaf36fd8803da5': string.Template('http://epg.tvsou.com/program/tv_81/channel_383/w${week}.htm'),  # 贵阳经济生活频道
    'abb0a775893eb5b2012481a347702fce': string.Template('http://epg.tvsou.com/program/tv_81/channel_384/w${week}.htm'),  # 贵阳都市频道
    '7c3474cea561ad99c0ebf1703a293f5b': string.Template('http://epg.tvsou.com/program/tv_81/channel_385/w${week}.htm'),  # 贵阳法制频道
    '19971bb5e4a32e334359109b646466f2': string.Template('http://epg.tvsou.com/program/tv_81/channel_386/w${week}.htm'),  # 贵阳旅游生活频道
    '9875b091185efcda92939d73195f9780': string.Template('http://epg.tvsou.com/program/tv_184/channel_815/w${week}.htm'),  # 安顺新闻频道
    'f47d16980eacab4d1dcb360dc5f5c9b7': string.Template('http://epg.tvsou.com/program/tv_184/channel_816/w${week}.htm'),  # 安顺影视频道
    '0d7b5dfe999fc5fd0140863f6e8910a5': string.Template('http://epg.tvsou.com/program/tv_8/channel_31/w${week}.htm'),  # 旅游卫视
    '33a42c2ea66097aae495d8d315b09a9d': string.Template('http://epg.tvsou.com/program/tv_55/channel_222/w${week}.htm'),  # 海南少儿频道
    '619234fdac8c2d80b5089e29bc63318d': string.Template('http://epg.tvsou.com/program/tv_55/channel_223/w${week}.htm'),  # 海南公共频道
    'd49ba196c99be1e1dcf8e2ed1c7f25c0': string.Template('http://epg.tvsou.com/program/tv_55/channel_224/w${week}.htm'),  # 海南影视娱乐频道
    'a76f567d38b0b063b94bf4c0a6794df0': string.Template('http://epg.tvsou.com/program/tv_55/channel_236/w${week}.htm'),  # 海南综合频道
    'b67d02b4623b7ea4a0918e5f7d63081e': string.Template('http://epg.tvsou.com/program/tv_124/channel_650/w${week}.htm'),  # 海口新闻综合频道
    '567002f7d705decd76f7280eeb98cfc5': string.Template('http://epg.tvsou.com/program/tv_124/channel_651/w${week}.htm'),  # 海口生活娱乐频道
    '2e5f058008421b693bd01a81a2c87c1a': string.Template('http://epg.tvsou.com/program/tv_124/channel_652/w${week}.htm'),  # 海口经济频道
    'ef1fce69a9e1b3a587ca734302400107': string.Template('http://epg.tvsou.com/program/tv_15/channel_37/w${week}.htm'),  # 河北卫视
    '0f8af22d6dcdb679f887167a1003cb3b': string.Template('http://epg.tvsou.com/program/tv_15/channel_318/w${week}.htm'),  # 河北经济生活频道
    '41494962aee2228895a8791846a57659': string.Template('http://epg.tvsou.com/program/tv_15/channel_319/w${week}.htm'),  # 河北都市频道
    '1bc83c6ebef2ea7f630accb6cb94bc29': string.Template('http://epg.tvsou.com/program/tv_15/channel_320/w${week}.htm'),  # 河北影视频道
    '874848eb50f3814693cb2564b03d6a9a': string.Template('http://epg.tvsou.com/program/tv_15/channel_321/w${week}.htm'),  # 河北少儿科教频道
    '5aa92ccc19f342469ee3f3f737fccc46': string.Template('http://epg.tvsou.com/program/tv_15/channel_322/w${week}.htm'),  # 河北公共频道
    'd1c8e849b4230766aeb23dd3704c3186': string.Template('http://epg.tvsou.com/program/tv_15/channel_323/w${week}.htm'),  # 河北农民频道
    '4fa492f440c0d5b4ab38da6aace5d6cb': string.Template('http://epg.tvsou.com/program/tv_79/channel_351/w${week}.htm'),  # 石家庄新闻综合频道
    '892fdb20d0e96b4d96381a99a1192ef6': string.Template('http://epg.tvsou.com/program/tv_79/channel_352/w${week}.htm'),  # 石家庄电视剧频道
    'cc2b3403b5ba9bff1579e1e4a50c044e': string.Template('http://epg.tvsou.com/program/tv_79/channel_353/w${week}.htm'),  # 石家庄电影频道
    'e7e09ab48eb59e71b2895216e1e8b0d5': string.Template('http://epg.tvsou.com/program/tv_79/channel_354/w${week}.htm'),  # 石家庄都市频道
    '26fbdd1667e50afbb5324b593c808d5f': string.Template('http://epg.tvsou.com/program/tv_128/channel_662/w${week}.htm'),  # 邢台新闻综合频道
    '160e4bc57ead5fce7a0656efed84932c': string.Template('http://epg.tvsou.com/program/tv_128/channel_663/w${week}.htm'),  # 邢台影视频道
    '7359a6f9fc4c9ed249f27ed73ac14dd2': string.Template('http://epg.tvsou.com/program/tv_128/channel_994/w${week}.htm'),  # 邢台有线台
    '77a9257087f8e8d21d0d3cf8e2f7449e': string.Template('http://epg.tvsou.com/program/tv_141/channel_715/w${week}.htm'),  # 廊坊新闻频道
    '45bd47b6c79337eed8c62620fb917d94': string.Template('http://epg.tvsou.com/program/tv_141/channel_716/w${week}.htm'),  # 廊坊公共频道
    '3f41155bc00c9b2aab01dec4aa815a94': string.Template('http://epg.tvsou.com/program/tv_191/channel_833/w${week}.htm'),  # 衡水公共频道
    '1f88e390b476512c70b0f4d39cb5b91f': string.Template('http://epg.tvsou.com/program/tv_191/channel_834/w${week}.htm'),  # 衡水新闻综合频道
    'a7b5ed2af93f1dbd5f76238d6136c287': string.Template('http://epg.tvsou.com/program/tv_212/channel_951/w${week}.htm'),  # 承德电视台-1
    'c7b5c0110b172f8793d712a03f79c002': string.Template('http://epg.tvsou.com/program/tv_212/channel_952/w${week}.htm'),  # 承德电视台-2
    '7ef67b051de4d708abee5071b43a9106': string.Template('http://epg.tvsou.com/program/tv_213/channel_953/w${week}.htm'),  # 邯郸电视台-1
    'a3b6e0d85602e9d7eac72cc9aae4218b': string.Template('http://epg.tvsou.com/program/tv_213/channel_954/w${week}.htm'),  # 邯郸电视台-2
    '5e05f59c28fcb78d7ce4362bfe3981a3': string.Template('http://epg.tvsou.com/program/tv_214/channel_955/w${week}.htm'),  # 保定新闻综合频道
    '06eab62841e2543b2e5df30f984c5601': string.Template('http://epg.tvsou.com/program/tv_214/channel_956/w${week}.htm'),  # 保定都市频道
    '9e7597abee6c8e8df7e96639536a924e': string.Template('http://epg.tvsou.com/program/tv_214/channel_1018/w${week}.htm'),  # 保定生活健康
    '3a702315705a665a5cf9910bdf618989': string.Template('http://epg.tvsou.com/program/tv_215/channel_957/w${week}.htm'),  # 秦皇岛电视台-1
    '1685c08ccb9b0f68438509081e79c5e7': string.Template('http://epg.tvsou.com/program/tv_215/channel_958/w${week}.htm'),  # 秦皇岛电视台-2
    '2c854868563485135dd486801057dd6e': string.Template('http://epg.tvsou.com/program/tv_16/channel_38/w${week}.htm'),  # 河南卫视
    '97fc3b35abb924bd5f22361e70c0c0d3': string.Template('http://epg.tvsou.com/program/tv_16/channel_326/w${week}.htm'),  # 河南都市频道
    'bcc91ed680fa4533ee10caa515b18d5c': string.Template('http://epg.tvsou.com/program/tv_16/channel_327/w${week}.htm'),  # 河南经济生活频道
    '7d623f92bd4723b670524078d2870eb1': string.Template('http://epg.tvsou.com/program/tv_16/channel_328/w${week}.htm'),  # 河南法制频道
    '4918c2f16e4f69d03b9c52efaf298a8c': string.Template('http://epg.tvsou.com/program/tv_16/channel_329/w${week}.htm'),  # 河南电视剧频道
    'bef6d1fad03628de32f0bdc329e255d9': string.Template('http://epg.tvsou.com/program/tv_16/channel_330/w${week}.htm'),  # 河南精品博览频道
    'b68340735367b7f85ff2b0bd3a262bc7': string.Template('http://epg.tvsou.com/program/tv_16/channel_331/w${week}.htm'),  # 河南商务信息频道
    '91b0452b67953f628a924580a4d9e0f5': string.Template('http://epg.tvsou.com/program/tv_16/channel_332/w${week}.htm'),  # 河南公共频道
    '98938fbafcfa3b1b5ecec5f42daabbe5': string.Template('http://epg.tvsou.com/program/tv_16/channel_653/w${week}.htm'),  # 河南新农村频道
    '6aeb4d3afc9f7ce4cf68c64f5b00e41a': string.Template('http://epg.tvsou.com/program/tv_76/channel_324/w${week}.htm'),  # 郑州新闻综合频道
    '233bb1d1359433e4aa5a93094789c649': string.Template('http://epg.tvsou.com/program/tv_76/channel_325/w${week}.htm'),  # 郑州二套
    '773e164518e463009bfaad76b13bdd1d': string.Template('http://epg.tvsou.com/program/tv_76/channel_587/w${week}.htm'),  # 郑州五套
    '943029ff0b6aabd5f3e593ed2e9cd55f': string.Template('http://epg.tvsou.com/program/tv_76/channel_588/w${week}.htm'),  # 郑州影视频道
    'a1527de157b492144b39bb422340012c': string.Template('http://epg.tvsou.com/program/tv_76/channel_589/w${week}.htm'),  # 郑州教育台
    'ff201d3dfa93f55339f50165a1242a3f': string.Template('http://epg.tvsou.com/program/tv_76/channel_643/w${week}.htm'),  # 郑州三套
    'e28240a7edbf60cc9953420bf6f8f80c': string.Template('http://epg.tvsou.com/program/tv_102/channel_530/w${week}.htm'),  # 洛阳新闻综合频道
    '40583d3569ff5e37de3bacbc44a9d7c5': string.Template('http://epg.tvsou.com/program/tv_102/channel_531/w${week}.htm'),  # 洛阳经济生活频道
    '030404f20418f350107f2f9ab5fe3c32': string.Template('http://epg.tvsou.com/program/tv_102/channel_532/w${week}.htm'),  # 洛阳科教法制频道
    'e3ac76943f04e2e9432c8137b538cc1b': string.Template('http://epg.tvsou.com/program/tv_102/channel_533/w${week}.htm'),  # 洛阳影视频道
    'b471c441d480756ea613894ab88d3664': string.Template('http://epg.tvsou.com/program/tv_131/channel_664/w${week}.htm'),  # 南阳新闻综合频道
    '1379dfee72a38ea56bb9d9ae3b04182d': string.Template('http://epg.tvsou.com/program/tv_131/channel_665/w${week}.htm'),  # 南阳社会生活频道
    'e52b17286ba21da0d456155e779ed7cc': string.Template('http://epg.tvsou.com/program/tv_131/channel_666/w${week}.htm'),  # 南阳精选文摘频道
    '0fe321900521c5f986e26be7da624909': string.Template('http://epg.tvsou.com/program/tv_139/channel_710/w${week}.htm'),  # 安阳都市频道
    '4ba3fe53fc535a1756430a8b3f4c5efd': string.Template('http://epg.tvsou.com/program/tv_139/channel_711/w${week}.htm'),  # 安阳新闻综合频道
    '4801b23615fdb33194a01fd89673a2f3': string.Template('http://epg.tvsou.com/program/tv_139/channel_712/w${week}.htm'),  # 安阳科教法制
    'e7b72de24dc2d39272baa46cd998b4ab': string.Template('http://epg.tvsou.com/program/tv_139/channel_722/w${week}.htm'),  # 安阳电视剧
    '1d2e94ea3697c60d888b721692ea6ddd': string.Template('http://epg.tvsou.com/program/tv_147/channel_734/w${week}.htm'),  # 新乡新闻综合频道
    'aacf4e7f32ccd1ee4fdfba8f182f0d2f': string.Template('http://epg.tvsou.com/program/tv_147/channel_959/w${week}.htm'),  # 新乡影视娱乐频道
    '7bf8f5b3b59532ec73fbab9dbe040fb9': string.Template('http://epg.tvsou.com/program/tv_147/channel_960/w${week}.htm'),  # 新乡法制频道
    'e04a1f964a0dd37ade61ac9e38f25e5d': string.Template('http://epg.tvsou.com/program/tv_147/channel_961/w${week}.htm'),  # 新乡生活频道
    '6f0486b770462e1df38da2c5c92493fa': string.Template('http://epg.tvsou.com/program/tv_147/channel_962/w${week}.htm'),  # 新乡教育频道
    '211123dbafa589b05156e54397252670': string.Template('http://epg.tvsou.com/program/tv_180/channel_808/w${week}.htm'),  # 鹤壁一套
    '42a7c898cfb8017052fd31a27c13001c': string.Template('http://epg.tvsou.com/program/tv_180/channel_809/w${week}.htm'),  # 鹤壁生活频道
    '2af471678b4afcd3d9ec3f87e4816a16': string.Template('http://epg.tvsou.com/program/tv_219/channel_973/w${week}.htm'),  # 信阳电视平桥频道
    'da3641e9e7c4acb6c261bcf6dfd1aef0': string.Template('http://epg.tvsou.com/program/tv_219/channel_974/w${week}.htm'),  # 信阳电视公共频道
    'b02b7b3a9267b371576f0d6b7f85d8f5': string.Template('http://epg.tvsou.com/program/tv_220/channel_975/w${week}.htm'),  # 平顶山新闻综合频道
    'c496ab8ae77ea24d61dd5980fb669b66': string.Template('http://epg.tvsou.com/program/tv_220/channel_976/w${week}.htm'),  # 平顶山影视剧频道
    'c8f957f1cf9453755e6ac23dcb470419': string.Template('http://epg.tvsou.com/program/tv_220/channel_977/w${week}.htm'),  # 平顶山公共频道
    '5bec0e32df910dc8d005134da78091c7': string.Template('http://epg.tvsou.com/program/tv_220/channel_978/w${week}.htm'),  # 平顶山教育频道
    'a0942d4ec837a899ee9b7ae79857dfa2': string.Template('http://epg.tvsou.com/program/tv_221/channel_979/w${week}.htm'),  # 周口综合频道
    '5dcbbeffa492be979b783fc25407454b': string.Template('http://epg.tvsou.com/program/tv_221/channel_980/w${week}.htm'),  # 周口经济生活频道
    '8708ef63624f9b95d963482a8f19f77a': string.Template('http://epg.tvsou.com/program/tv_221/channel_981/w${week}.htm'),  # 周口电视剧频道
    '40773064b87c1f27d736d4837eec265c': string.Template('http://epg.tvsou.com/program/tv_221/channel_988/w${week}.htm'),  # 周口商务信息频道
    '619a2212b9f0b8befa29c9f18d932c4d': string.Template('http://epg.tvsou.com/program/tv_222/channel_982/w${week}.htm'),  # 许昌新闻综合频道
    'b384fa4c896155ce3bc1c3c1304ca8fd': string.Template('http://epg.tvsou.com/program/tv_222/channel_983/w${week}.htm'),  # 许昌公共频道
    '34870ffe11660db5ff855b2410f2b0fb': string.Template('http://epg.tvsou.com/program/tv_223/channel_985/w${week}.htm'),  # 濮阳一套
    '141f7c044be24568c4cc05cbe8a0bf9f': string.Template('http://epg.tvsou.com/program/tv_223/channel_985/w${week}.htm'),  # 濮阳二套
    '4dbd871d1685d221eb7a56ee28c0080e': string.Template('http://epg.tvsou.com/program/tv_223/channel_986/w${week}.htm'),  # 濮阳三套
    'aea502801c1d79c055e02c44989c689b': string.Template('http://epg.tvsou.com/program/tv_223/channel_987/w${week}.htm'),  # 中原石油有线电视
    'd51b40e3302c785d791ab971c754f0cd': string.Template('http://epg.tvsou.com/program/tv_224/channel_989/w${week}.htm'),  # 驻马店一套
    '788d01967493fa2218e3d736b4cdb241': string.Template('http://epg.tvsou.com/program/tv_224/channel_989/w${week}.htm'),  # 驻马店商务信息台
    '23b053d249f59210a53c72e2f571a439': string.Template('http://epg.tvsou.com/program/tv_231/channel_1035/w${week}.htm'),  # 焦作电视台一套
    'f62236d21f74701afe339430e53cc867': string.Template('http://epg.tvsou.com/program/tv_231/channel_1036/w${week}.htm'),  # 焦作电视台二套
    '79fa1e2483d4f4bfa137eb4c24b4e290': string.Template('http://epg.tvsou.com/program/tv_231/channel_1037/w${week}.htm'),  # 焦作电视台三套
    'c575cf5b986dcc426a1d8d0fbda4f41c': string.Template('http://epg.tvsou.com/program/tv_232/channel_1033/w${week}.htm'),  # 开封电视台一套
    '1af5968af3d8bf4f9b1cde812383c9ba': string.Template('http://epg.tvsou.com/program/tv_232/channel_1034/w${week}.htm'),  # 开封教育台
    'b6634230ae08eaef04cc29ff79e52461': string.Template('http://epg.tvsou.com/program/tv_234/channel_1042/w${week}.htm'),  # 三门峡一套
    'dd4e6934a67e280a0359f8b9ecb9c063': string.Template('http://epg.tvsou.com/program/tv_234/channel_1043/w${week}.htm'),  # 三门峡公共频道
    '1ce026a774dba0d13dc0cef453248fb7': string.Template('http://epg.tvsou.com/program/tv_12/channel_34/w${week}.htm'),  # 黑龙江卫视
    'e8cf5237122395f459370bc68c5c8443': string.Template('http://epg.tvsou.com/program/tv_12/channel_387/w${week}.htm'),  # 黑龙江影视频道
    '553b5dbba7190e6e607850193b6e2196': string.Template('http://epg.tvsou.com/program/tv_12/channel_388/w${week}.htm'),  # 黑龙江文艺频道
    'ce32f3e4a1277690f9204f4d4addca74': string.Template('http://epg.tvsou.com/program/tv_12/channel_389/w${week}.htm'),  # 黑龙江女性频道
    '3ff2ba4b0be90977b15202e22cdff8ce': string.Template('http://epg.tvsou.com/program/tv_12/channel_390/w${week}.htm'),  # 黑龙江法制频道
    '8eaea57f2c63eb394858828aa3ced3da': string.Template('http://epg.tvsou.com/program/tv_12/channel_391/w${week}.htm'),  # 黑龙江公共频道
    '6427155c889b99cf1400d905251dbdaf': string.Template('http://epg.tvsou.com/program/tv_12/channel_392/w${week}.htm'),  # 黑龙江少儿频道
    '3fb7065690619547071ae2043e9afbb3': string.Template('http://epg.tvsou.com/program/tv_82/channel_393/w${week}.htm'),  # 哈尔滨新闻综合频道
    'fbd81165e700c905dd1ec795fc1c5850': string.Template('http://epg.tvsou.com/program/tv_82/channel_394/w${week}.htm'),  # 哈尔滨都市资讯
    '6ca5828cc7abc19d7c01441623415fe4': string.Template('http://epg.tvsou.com/program/tv_82/channel_395/w${week}.htm'),  # 哈尔滨生活频道
    'ce905c90a0ffe43da23f22abb9ac1fc9': string.Template('http://epg.tvsou.com/program/tv_82/channel_396/w${week}.htm'),  # 哈尔滨娱乐频道
    '517fca2701c54d8c8ac0bddc03f52db9': string.Template('http://epg.tvsou.com/program/tv_82/channel_397/w${week}.htm'),  # 哈尔滨影视频道
    '521b67f193167daabd7a1fa8655cb064': string.Template('http://epg.tvsou.com/program/tv_196/channel_852/w${week}.htm'),  # 齐齐哈尔新闻频道
    '71509f37a5f1cccf1d40abc4351190fb': string.Template('http://epg.tvsou.com/program/tv_196/channel_853/w${week}.htm'),  # 齐齐哈尔影视频道
    '0caed11f7235c0ffdef17c43c8630b31': string.Template('http://epg.tvsou.com/program/tv_196/channel_854/w${week}.htm'),  # 齐齐哈尔都市
    '0d79751fc328a460086be9c2b5825ef1': string.Template('http://epg.tvsou.com/program/tv_205/channel_899/w${week}.htm'),  # 大庆新闻综合频道
    '5d43b6d34303de104e43074e65a766eb': string.Template('http://epg.tvsou.com/program/tv_205/channel_900/w${week}.htm'),  # 大庆影视频道
    '8004fc965ab2cb505d42e13537fc8724': string.Template('http://epg.tvsou.com/program/tv_205/channel_901/w${week}.htm'),  # 大庆娱乐生活频道
    '25d2036e6dd898be583455103d423f80': string.Template('http://epg.tvsou.com/program/tv_205/channel_902/w${week}.htm'),  # 大庆教育频道
    '8867a50577c9b4f08e0b9bf2558d0dde': string.Template('http://epg.tvsou.com/program/tv_205/channel_903/w${week}.htm'),  # 大庆油田有线电视
    '55fc65ef82e92d0e1ccb2b3f200a7529': string.Template('http://epg.tvsou.com/program/tv_25/channel_47/w${week}.htm'),  # 湖北卫视
    '0a22cb6733af1d1f834700dfbfc7e0e5': string.Template('http://epg.tvsou.com/program/tv_25/channel_131/w${week}.htm'),  # 湖北一套
    'cef3d66c4926ac90536c98e50af8033c': string.Template('http://epg.tvsou.com/program/tv_25/channel_132/w${week}.htm'),  # 湖北影视频道
    'b4edfde5a95f13c874fabdc87dc56deb': string.Template('http://epg.tvsou.com/program/tv_25/channel_133/w${week}.htm'),  # 湖北教育频道
    'e55a8fb0ee3f7373f3a1e47f631247a0': string.Template('http://epg.tvsou.com/program/tv_25/channel_134/w${week}.htm'),  # 湖北体育频道
    '072ab9beb89dcb491cf483479674a69a': string.Template('http://epg.tvsou.com/program/tv_25/channel_135/w${week}.htm'),  # 湖北都市频道
    '19d87d97ae9b79d8213eac972ea49583': string.Template('http://epg.tvsou.com/program/tv_25/channel_136/w${week}.htm'),  # 湖北公共频道
    '347c78776c567bddc27b1a618b54b896': string.Template('http://epg.tvsou.com/program/tv_25/channel_635/w${week}.htm'),  # 湖北经济频道
    'fcd20437a38f5afe7bf6598647e7c4a8': string.Template('http://epg.tvsou.com/program/tv_53/channel_144/w${week}.htm'),  # 武汉一套
    '0111e010d7f06c067518f08cd808e195': string.Template('http://epg.tvsou.com/program/tv_53/channel_145/w${week}.htm'),  # 武汉二套
    '9a759ac93cf69db3c963070c7f8df647': string.Template('http://epg.tvsou.com/program/tv_53/channel_146/w${week}.htm'),  # 武汉三套
    '1ed3a457f98823406e569076e4f4fa52': string.Template('http://epg.tvsou.com/program/tv_53/channel_147/w${week}.htm'),  # 武汉四套
    'bd081e44226e917cc49693d459d392da': string.Template('http://epg.tvsou.com/program/tv_53/channel_148/w${week}.htm'),  # 武汉五套
    '96475f8b34f2dc2e587995a1959acd80': string.Template('http://epg.tvsou.com/program/tv_53/channel_149/w${week}.htm'),  # 武汉六套
    'e66827f15987fb8f916ffef279d02296': string.Template('http://epg.tvsou.com/program/tv_53/channel_150/w${week}.htm'),  # 武汉七套
    '2b995adf790a533df2d79b246f08821c': string.Template('http://epg.tvsou.com/program/tv_53/channel_661/w${week}.htm'),  # 武汉消费指南频道
    '6db79e95f479d7ca1cde53778075987f': string.Template('http://epg.tvsou.com/program/tv_53/channel_1010/w${week}.htm'),  # 武汉教育台
    'dffdd3bc5f38485891d239eed0c29065': string.Template('http://epg.tvsou.com/program/tv_69/channel_247/w${week}.htm'),  # 荆州新闻频道
    '5eef0524d8ec23e1963db11ab87625c4': string.Template('http://epg.tvsou.com/program/tv_69/channel_248/w${week}.htm'),  # 荆州电视精彩频道
    '9d11eea798e972b30b74cd5b5fa2363f': string.Template('http://epg.tvsou.com/program/tv_69/channel_249/w${week}.htm'),  # 荆州电视公共频道
    'a8a84aa51d586f8d97f93a4ff1b1fb7a': string.Template('http://epg.tvsou.com/program/tv_145/channel_728/w${week}.htm'),  # 襄樊电视台-1
    '00dbf4d389cf2a6d6595657989de562d': string.Template('http://epg.tvsou.com/program/tv_145/channel_729/w${week}.htm'),  # 襄樊电视台-2
    '2f6686b8e3637c31500bd99ce65d55cd': string.Template('http://epg.tvsou.com/program/tv_145/channel_730/w${week}.htm'),  # 襄樊电视台-3
    'dc7a0216a70fbeb4c649a3677888ae2a': string.Template('http://epg.tvsou.com/program/tv_157/channel_746/w${week}.htm'),  # 宜昌综合频道
    '3242003143a76f938a9bb02644f217cc': string.Template('http://epg.tvsou.com/program/tv_157/channel_747/w${week}.htm'),  # 宜昌公共频道
    '8892d1e3df3c106d477a140f90beeff2': string.Template('http://epg.tvsou.com/program/tv_157/channel_748/w${week}.htm'),  # 宜昌影视频道
    '0489139ceb71d5db32052d1ae2e05ed0': string.Template('http://epg.tvsou.com/program/tv_160/channel_758/w${week}.htm'),  # 十堰新闻频道
    '2dae5767f0b48f92eacf860f96b58111': string.Template('http://epg.tvsou.com/program/tv_160/channel_759/w${week}.htm'),  # 十堰综合频道
    '83253cd2310e25e81291c9739e20b400': string.Template('http://epg.tvsou.com/program/tv_160/channel_760/w${week}.htm'),  # 十堰经济生活频道
    'db44623b28850147c49f4a8a35f7acfc': string.Template('http://epg.tvsou.com/program/tv_160/channel_761/w${week}.htm'),  # 十堰信息咨讯
    '83683dc825ba9b671c2c09b3b1351e38': string.Template('http://epg.tvsou.com/program/tv_178/channel_805/w${week}.htm'),  # 荆门公共频道
    '7f6b09bae7b12843e2bea2913cb3befb': string.Template('http://epg.tvsou.com/program/tv_178/channel_806/w${week}.htm'),  # 荆门新闻频道
    '27e9a925fa857c0a72dc870acf641135': string.Template('http://epg.tvsou.com/program/tv_178/channel_807/w${week}.htm'),  # 荆门科技农林频道
    '9ee9c37c79599a6e52b9e6030a450b9a': string.Template('http://epg.tvsou.com/program/tv_181/channel_810/w${week}.htm'),  # 黄冈新闻频道
    '0af62f79667ef1a3900f8d36eb79dbb9': string.Template('http://epg.tvsou.com/program/tv_181/channel_811/w${week}.htm'),  # 黄冈公共频道
    'c39a7a374d888bce3912df71bcb0d580': string.Template('http://epg.tvsou.com/program/tv_24/channel_46/w${week}.htm'),  # 湖南卫视
    '370e3081d7630e9fe35125bd6dab01da': string.Template('http://epg.tvsou.com/program/tv_24/channel_129/w${week}.htm'),  # 湖南金鹰卡通
    '16ab3b6b59efcc897e138b905d0453cf': string.Template('http://epg.tvsou.com/program/tv_24/channel_435/w${week}.htm'),  # 湖南娱乐频道
    'd6df323691d8b8b583dbc6a0a42de1c8': string.Template('http://epg.tvsou.com/program/tv_24/channel_436/w${week}.htm'),  # 湖南影视频道
    '09a03743fe524bf6d65eddb26b672725': string.Template('http://epg.tvsou.com/program/tv_24/channel_437/w${week}.htm'),  # 湖南公共频道
    '1b0133378610629d2d86db58ee2a13d0': string.Template('http://epg.tvsou.com/program/tv_24/channel_594/w${week}.htm'),  # 潇湘电影频道
    '886aaa6fdc78ea7913bcd348d5521723': string.Template('http://epg.tvsou.com/program/tv_24/channel_721/w${week}.htm'),  # 湖南教育频道
    'cdf3154825a3fcb8d8f22ede1aebad6d': string.Template('http://epg.tvsou.com/program/tv_50/channel_126/w${week}.htm'),  # eTV综合频道
    '7e8ee6307f82e6c880e33eac7bee9eee': string.Template('http://epg.tvsou.com/program/tv_50/channel_127/w${week}.htm'),  # eTV都市频道
    '00a37fc4035e5e3467fbd2276b2d8996': string.Template('http://epg.tvsou.com/program/tv_50/channel_128/w${week}.htm'),  # eTV生活频道
    '2be54458b6724c4587631ea9b1e965b6': string.Template('http://epg.tvsou.com/program/tv_51/channel_137/w${week}.htm'),  # 长沙女性频道
    '4f3f66d3694ef7d28a4e95cd36add07d': string.Template('http://epg.tvsou.com/program/tv_51/channel_138/w${week}.htm'),  # 长沙政法频道
    '005b778d9c77b5c52f7a5c21c1004748': string.Template('http://epg.tvsou.com/program/tv_51/channel_646/w${week}.htm'),  # 长沙经贸频道
    '980509be550a878e18799506d25da5e0': string.Template('http://epg.tvsou.com/program/tv_51/channel_647/w${week}.htm'),  # 长沙新闻频道
    'ed4f1c95b643b46096f4d86b0acc5c4f': string.Template('http://epg.tvsou.com/program/tv_51/channel_648/w${week}.htm'),  # 长沙公共频道
    '2345f52d2d48fa63ce0b2d71ded9bffe': string.Template('http://epg.tvsou.com/program/tv_136/channel_701/w${week}.htm'),  # 常德新闻频道
    'ad55a6faa81c5166871be208e17a4d74': string.Template('http://epg.tvsou.com/program/tv_136/channel_702/w${week}.htm'),  # 常德公共频道
    'b12244dec166a48deb19e788a558c9eb': string.Template('http://epg.tvsou.com/program/tv_136/channel_703/w${week}.htm'),  # 常德都市频道
    'bebc9497630e02b06d19941872f94772': string.Template('http://epg.tvsou.com/program/tv_136/channel_765/w${week}.htm'),  # 鼎城电视频道
    '88752a73e01d617d245ab2b6b43d6808': string.Template('http://epg.tvsou.com/program/tv_137/channel_704/w${week}.htm'),  # 湘潭都市频道
    '8078af82b2fc071332328677c719e48e': string.Template('http://epg.tvsou.com/program/tv_137/channel_705/w${week}.htm'),  # 湘潭法制频道
    'ea72f6bb317200360a9f9d2289a566f5': string.Template('http://epg.tvsou.com/program/tv_137/channel_706/w${week}.htm'),  # 湘潭新闻频道
    '8b6e1966fd65a410ed8c821790e44b68': string.Template('http://epg.tvsou.com/program/tv_183/channel_814/w${week}.htm'),  # 衡阳台
    'a19f1a7c8c89a3ad6da6dd4ee90d8fa6': string.Template('http://epg.tvsou.com/program/tv_201/channel_881/w${week}.htm'),  # 岳阳新闻频道
    '3fb27066db6f5606d0f546208ddfd307': string.Template('http://epg.tvsou.com/program/tv_201/channel_882/w${week}.htm'),  # 岳阳公共频道
    '3c73766b94da7dfd170bf0fc3f5c6861': string.Template('http://epg.tvsou.com/program/tv_201/channel_883/w${week}.htm'),  # 岳阳科教频道
    '4416bf370c9854422e283042b1b112da': string.Template('http://epg.tvsou.com/program/tv_233/channel_1038/w${week}.htm'),  # 株洲新闻综合频道
    '09f8301784fa9c6a0213e5b2a55e390f': string.Template('http://epg.tvsou.com/program/tv_233/channel_1039/w${week}.htm'),  # 株洲经济频道
    '510b6f7c13444bf2e243a2fa7a0606b0': string.Template('http://epg.tvsou.com/program/tv_233/channel_1040/w${week}.htm'),  # 株洲公共频道
    '45392a8be644f5b8903838436870c75d': string.Template('http://epg.tvsou.com/program/tv_13/channel_35/w${week}.htm'),  # 吉林卫视
    '20a9891e012bbca9a1ef7feb294bdac0': string.Template('http://epg.tvsou.com/program/tv_13/channel_363/w${week}.htm'),  # 吉视都市
    '37cf92a497638273a0bc39b827e1e629': string.Template('http://epg.tvsou.com/program/tv_13/channel_364/w${week}.htm'),  # 吉视文体
    '032a1ca1cb13547e541344ad1ab8e547': string.Template('http://epg.tvsou.com/program/tv_13/channel_365/w${week}.htm'),  # 吉视影视
    'e06a29de48a3504e0e95bfb775f1b343': string.Template('http://epg.tvsou.com/program/tv_13/channel_366/w${week}.htm'),  # 吉视乡村
    '418bf9a5cb13b87584acf8551895a2bc': string.Template('http://epg.tvsou.com/program/tv_13/channel_367/w${week}.htm'),  # 吉视公共
    '58cf2e4e072a28d43c40433599cd8456': string.Template('http://epg.tvsou.com/program/tv_116/channel_598/w${week}.htm'),  # 长春一套
    '5e41970832895606ec805c834e527e26': string.Template('http://epg.tvsou.com/program/tv_116/channel_599/w${week}.htm'),  # 长春二套
    '5483d77fc6adc5979f33fd2ebebb8cc9': string.Template('http://epg.tvsou.com/program/tv_116/channel_600/w${week}.htm'),  # 长春三套
    '523441ca1439d086568b04cb7660584e': string.Template('http://epg.tvsou.com/program/tv_116/channel_601/w${week}.htm'),  # 长春四套
    'a0b66d8fdd383cf8daaa6e10a3c4963e': string.Template('http://epg.tvsou.com/program/tv_116/channel_602/w${week}.htm'),  # 长春五套
    'b2d36dab58b539d42ef2fd4bc4f92624': string.Template('http://epg.tvsou.com/program/tv_209/channel_946/w${week}.htm'),  # 吉林教育频道
    'a314af229ed0b28e756f9cec817a2735': string.Template('http://epg.tvsou.com/program/tv_226/channel_1019/w${week}.htm'),  # 吉林市新闻
    '5c48e9e1b29f24ae7dcd129bd234b6ec': string.Template('http://epg.tvsou.com/program/tv_226/channel_1020/w${week}.htm'),  # 吉林市生活
    'eb239412150b03f8b45253f8b405dacd': string.Template('http://epg.tvsou.com/program/tv_226/channel_1021/w${week}.htm'),  # 吉林市娱乐
    '817611bbe8e45412d8e30ff5f2d4c804': string.Template('http://epg.tvsou.com/program/tv_226/channel_1022/w${week}.htm'),  # 吉林市电视剧
    '7914a1697ae76379f37a8a1a763d32aa': string.Template('http://epg.tvsou.com/program/tv_226/channel_1023/w${week}.htm'),  # 吉林市电影
    '7897f487075a9d9387ed1481192e16e7': string.Template('http://epg.tvsou.com/program/tv_236/channel_1045/w${week}.htm'),  # 松原新闻综合
    '59802f06f27bd66616f5c5b73248f82f': string.Template('http://epg.tvsou.com/program/tv_236/channel_1046/w${week}.htm'),  # 松原经济生活
    '8691536d47fce9d4ef515de30f728222': string.Template('http://epg.tvsou.com/program/tv_236/channel_1047/w${week}.htm'),  # 松原少儿影视
    '322fa7b66243b8d0edef9d761a42f263': string.Template('http://epg.tvsou.com/program/tv_22/channel_44/w${week}.htm'),  # 江苏卫视
    '8997a5a46f2b2f73af589e7075fde1ff': string.Template('http://epg.tvsou.com/program/tv_22/channel_111/w${week}.htm'),  # 江苏城市频道
    '70d3931ad7b8a08380027e10b9f6a8db': string.Template('http://epg.tvsou.com/program/tv_22/channel_113/w${week}.htm'),  # 江苏综艺频道
    '875a32b06f39cec9178403c104a29418': string.Template('http://epg.tvsou.com/program/tv_22/channel_115/w${week}.htm'),  # 江苏影视频道
    '35482ad8ed0e51daaed52b3307282520': string.Template('http://epg.tvsou.com/program/tv_22/channel_118/w${week}.htm'),  # 江苏公共频道
    '600bd02be669f8c255d12704fa37fa30': string.Template('http://epg.tvsou.com/program/tv_22/channel_120/w${week}.htm'),  # 江苏体育频道
    'jsintertv_asia': string.Template('http://epg.tvsou.com/program/tv_22/channel_1262/w${week}.htm'),  # 江苏国际频道(亚洲)
    'jsintertv_europe': string.Template('http://epg.tvsou.com/program/tv_22/channel_1585/w${week}.htm'),  # 江苏国际频道(欧洲)
    'jsintertv_america': string.Template('http://epg.tvsou.com/program/tv_22/channel_1599/w${week}.htm'),  # 江苏国际频道(美洲)
    'a4fd1583e69f17bdb8983382b115222f': string.Template('http://epg.tvsou.com/program/tv_22/channel_123/w${week}.htm'),  # 江苏少儿频道
    '6a12341152e41576d5107eae44a4fef8': string.Template('http://epg.tvsou.com/program/tv_22/channel_125/w${week}.htm'),  # 江苏靓妆频道
    'e1aa06acd0fec68c4574d9b6d4129d15': string.Template('http://epg.tvsou.com/program/tv_22/channel_999/w${week}.htm'),  # 江苏教育频道
    '8486f91868f3e6d4f4d6517ca2c2c017': string.Template('http://epg.tvsou.com/program/tv_49/channel_110/w${week}.htm'),  # 南京新闻综合频道
    '7f0bc7666fadfdbab99f00e79e9d6eed': string.Template('http://epg.tvsou.com/program/tv_49/channel_112/w${week}.htm'),  # 南京教育科技频道
    'f702e2980d1d5d07ec19ab7dd8a1d4df': string.Template('http://epg.tvsou.com/program/tv_49/channel_114/w${week}.htm'),  # 南京影视频道
    'c817d4455a6958f4c978bf805a6befe3': string.Template('http://epg.tvsou.com/program/tv_49/channel_116/w${week}.htm'),  # 南京少儿频道
    'a2ddaf31b63b0f4912c4b637ac18fa11': string.Template('http://epg.tvsou.com/program/tv_49/channel_117/w${week}.htm'),  # 南京文体频道
    'c7b4f13f56db73dd19b00f59cac59e6f': string.Template('http://epg.tvsou.com/program/tv_49/channel_119/w${week}.htm'),  # 南京生活频道
    '7aae5790363837c8391effbd38a901ae': string.Template('http://epg.tvsou.com/program/tv_49/channel_121/w${week}.htm'),  # 南京股市信息频道
    'c2bb69888fed8681a09c6a084cac5ba7': string.Template('http://epg.tvsou.com/program/tv_49/channel_124/w${week}.htm'),  # 南京十八频道
    '780e26a32bcc0a63a9fbcda28729084b': string.Template('http://epg.tvsou.com/program/tv_49/channel_1000/w${week}.htm'),  # 南京娱乐频道
    '9ad2fae07181e36e22daf75c91787482': string.Template('http://epg.tvsou.com/program/tv_52/channel_139/w${week}.htm'),  # 苏州新闻综合频道
    '446bcbdbfc468a0d05c78aa90ab0ba95': string.Template('http://epg.tvsou.com/program/tv_52/channel_140/w${week}.htm'),  # 苏州社会经济频道
    '4514ee74b0ceca00c2f078fe16f4dcec': string.Template('http://epg.tvsou.com/program/tv_52/channel_141/w${week}.htm'),  # 苏州文化生活频道
    '3f5bc1b375c7852e1223dc5389bce8d9': string.Template('http://epg.tvsou.com/program/tv_52/channel_142/w${week}.htm'),  # 苏州电影娱乐频道
    '504d137f1d5292dc1cfeda7ce3e04d58': string.Template('http://epg.tvsou.com/program/tv_52/channel_143/w${week}.htm'),  # 苏州生活资讯频道
    '43e51420f52c587dce3935a1a3e3bed6': string.Template('http://epg.tvsou.com/program/tv_98/channel_506/w${week}.htm'),  # 无锡新闻综合频道
    'efb26ba80874be5a71fc15313026aa0d': string.Template('http://epg.tvsou.com/program/tv_98/channel_507/w${week}.htm'),  # 无锡娱乐频道
    '8351d286645825fd1e563ceddbee4b9f': string.Template('http://epg.tvsou.com/program/tv_98/channel_508/w${week}.htm'),  # 无锡经济频道
    'e0c9bf74302e391aee1212317ffc674a': string.Template('http://epg.tvsou.com/program/tv_98/channel_509/w${week}.htm'),  # 无锡都市资讯频道
    '7d1d8a56c9df9f52110c1fc3d5443831': string.Template('http://epg.tvsou.com/program/tv_98/channel_510/w${week}.htm'),  # 无锡健康频道
    'd59b39765720d957add5ec4c7e25106e': string.Template('http://epg.tvsou.com/program/tv_98/channel_511/w${week}.htm'),  # 无锡影视频道
    '6a852c2d64fe4b61dc9a4743e4806322': string.Template('http://epg.tvsou.com/program/tv_98/channel_672/w${week}.htm'),  # 无锡教育频道
    '521c921554a42d9b07b6529a41a29da7': string.Template('http://epg.tvsou.com/program/tv_98/channel_880/w${week}.htm'),  # 无锡体育频道
    '40c34112738ce416e82842113dde58d7': string.Template('http://epg.tvsou.com/program/tv_99/channel_512/w${week}.htm'),  # 徐州新闻综合频道
    '13a9cd4ac81c0570cac841c95f4fbba9': string.Template('http://epg.tvsou.com/program/tv_99/channel_513/w${week}.htm'),  # 徐州经济生活频道
    '7756c9606de07830da4a48bc6451808d': string.Template('http://epg.tvsou.com/program/tv_99/channel_514/w${week}.htm'),  # 徐州文艺影视频道
    '8a466a3f184e0a0941d61c4f038fd835': string.Template('http://epg.tvsou.com/program/tv_99/channel_515/w${week}.htm'),  # 徐州社会政法频道
    '9d18178caca37174fdc1dde9c409effc': string.Template('http://epg.tvsou.com/program/tv_125/channel_654/w${week}.htm'),  # 常州新闻频道
    '8dbc52e3702870dbd7a5f3f23c920877': string.Template('http://epg.tvsou.com/program/tv_125/channel_655/w${week}.htm'),  # 常州都市频道
    'e95113dce39a6a34786ff6dd237f3e76': string.Template('http://epg.tvsou.com/program/tv_125/channel_656/w${week}.htm'),  # 常州影视频道
    '393e1a40c0e8dd177fa37a28d99b08f2': string.Template('http://epg.tvsou.com/program/tv_125/channel_657/w${week}.htm'),  # 常州公共综艺频道
    'b738759b11b385d52be5645448f1b8de': string.Template('http://epg.tvsou.com/program/tv_125/channel_688/w${week}.htm'),  # 常州图文频道
    '88171babd46900d5bce691417756be5b': string.Template('http://epg.tvsou.com/program/tv_163/channel_766/w${week}.htm'),  # 南通1套
    '4a5ef61ce7385ebeaf876933a7e724c9': string.Template('http://epg.tvsou.com/program/tv_163/channel_767/w${week}.htm'),  # 南通2套
    '8b8912577cce780b02de086eeaf0bb3d': string.Template('http://epg.tvsou.com/program/tv_163/channel_768/w${week}.htm'),  # 南通3套
    '4e925736616cd1ff90648ffe45cde95e': string.Template('http://epg.tvsou.com/program/tv_163/channel_769/w${week}.htm'),  # 南通4套
    'e262de63a5f6822989619dea5ac3d988': string.Template('http://epg.tvsou.com/program/tv_167/channel_780/w${week}.htm'),  # 泰州影视娱乐频道
    '6d88009aeb43b4c5a93eaa97f695ea68': string.Template('http://epg.tvsou.com/program/tv_167/channel_781/w${week}.htm'),  # 泰州经济生活频道
    '0739d53d0fe7f3d54d12293a7029a468': string.Template('http://epg.tvsou.com/program/tv_167/channel_782/w${week}.htm'),  # 泰州新闻综合频道
    'dfc89b76161976f485a7e3c048e86587': string.Template('http://epg.tvsou.com/program/tv_176/channel_799/w${week}.htm'),  # 江阴新闻综合频道
    '5d15d059050f71db6b5d9ccbc47eaa83': string.Template('http://epg.tvsou.com/program/tv_176/channel_800/w${week}.htm'),  # 江阴城市频道
    '7c16af8e2759453795def54253fa6bb0': string.Template('http://epg.tvsou.com/program/tv_176/channel_801/w${week}.htm'),  # 江阴电影频道
    '8d7ed5136340221e39d5179d89ef0e65': string.Template('http://epg.tvsou.com/program/tv_176/channel_802/w${week}.htm'),  # 江阴电视剧频道
    '535765a19ab55a12bbf64a1e98ae97dd': string.Template('http://epg.tvsou.com/program/tv_23/channel_45/w${week}.htm'),  # 江西卫视
    '347316916e89c906c0585b558da8b62b': string.Template('http://epg.tvsou.com/program/tv_23/channel_216/w${week}.htm'),  # 江西电视台-2
    'd74d193840fea59e019dd50f253eee74': string.Template('http://epg.tvsou.com/program/tv_23/channel_217/w${week}.htm'),  # 江西电视台-3
    '4779c6594b46c1bf39aff9a48839c692': string.Template('http://epg.tvsou.com/program/tv_23/channel_218/w${week}.htm'),  # 江西电视台-4
    'c7aac447a8bd48d960043f5e46c49e7f': string.Template('http://epg.tvsou.com/program/tv_23/channel_244/w${week}.htm'),  # 江西电视台-5
    'cdffc914179fc1e64185b5669adab116': string.Template('http://epg.tvsou.com/program/tv_23/channel_245/w${week}.htm'),  # 江西电视台-6
    '38a587579d50d0b0346ad6d105eee7ba': string.Template('http://epg.tvsou.com/program/tv_90/channel_489/w${week}.htm'),  # 江西教育台
    'a9e5c469215f9f1b7a956e419da8bd1a': string.Template('http://epg.tvsou.com/program/tv_91/channel_490/w${week}.htm'),  # 南昌电视台-1
    '1c09ea5a08f8249810b15ad43c40bcdb': string.Template('http://epg.tvsou.com/program/tv_91/channel_491/w${week}.htm'),  # 南昌电视台-2
    '224b1f02828cfef51968b4390a47e048': string.Template('http://epg.tvsou.com/program/tv_91/channel_492/w${week}.htm'),  # 南昌电视台-3
    '41f9a0e508b7bff7abed987f52a7a65c': string.Template('http://epg.tvsou.com/program/tv_91/channel_493/w${week}.htm'),  # 南昌电视台-4
    'd784dd8e2d9e435b562da0da1e833de4': string.Template('http://epg.tvsou.com/program/tv_92/channel_494/w${week}.htm'),  # 赣州一套
    '024cd992d31cfcf133949623de138ac3': string.Template('http://epg.tvsou.com/program/tv_92/channel_495/w${week}.htm'),  # 赣州二套
    '0954954a291a6937ea236fd4e74a4233': string.Template('http://epg.tvsou.com/program/tv_154/channel_749/w${week}.htm'),  # 萍乡新闻综合频道
    '29e3b78e95dc306fb0a3a5fba0f0ae62': string.Template('http://epg.tvsou.com/program/tv_154/channel_750/w${week}.htm'),  # 萍乡都市频道
    'ba50be6289ed3a1884e1a7c3f9fdd143': string.Template('http://epg.tvsou.com/program/tv_154/channel_751/w${week}.htm'),  # 萍乡教育频道
    'ddcdeed2eb8d94bf37c9a86fe1615271': string.Template('http://epg.tvsou.com/program/tv_177/channel_803/w${week}.htm'),  # 九江一套
    'dbdad7cb317a6329bf0e88c20fe67292': string.Template('http://epg.tvsou.com/program/tv_177/channel_804/w${week}.htm'),  # 九江公共频道
    'a2adac981c6793c81fb261aacbf7fe74': string.Template('http://epg.tvsou.com/program/tv_177/channel_1050/w${week}.htm'),  # 九江三套
    'c527ecba7a6d5b2eb4c2a4dc59ee6e74': string.Template('http://epg.tvsou.com/program/tv_206/channel_904/w${week}.htm'),  # 新余一套
    '3804d10636ba9e5bcb2a886ea10dae31': string.Template('http://epg.tvsou.com/program/tv_206/channel_905/w${week}.htm'),  # 新余二套
    '4f3130671bed3ee6ff478d89cf30ded6': string.Template('http://epg.tvsou.com/program/tv_207/channel_906/w${week}.htm'),  # 鹰潭新闻综合频道
    '75b1e229782de063385588bce8df5a27': string.Template('http://epg.tvsou.com/program/tv_207/channel_907/w${week}.htm'),  # 鹰潭公共频道
    '9bf46e502069cc1e40a319229a64388e': string.Template('http://epg.tvsou.com/program/tv_210/channel_947/w${week}.htm'),  # 抚州综合频道
    '6bb6582b66245c929b34210c3a7d5c18': string.Template('http://epg.tvsou.com/program/tv_210/channel_948/w${week}.htm'),  # 抚州公共频道
    '921f80c82927664e028789c67b8b4958': string.Template('http://epg.tvsou.com/program/tv_211/channel_949/w${week}.htm'),  # 上饶一套
    '6c433a5db4daae6472b07dffeac9c4b9': string.Template('http://epg.tvsou.com/program/tv_211/channel_950/w${week}.htm'),  # 上饶二套
    '4be0b55ad80a80a4638a8b7228e7a1ef': string.Template('http://epg.tvsou.com/program/tv_237/channel_1051/w${week}.htm'),  # 宜春一套
    '3b1bebcafdbc5b2980ced6e881dce49c': string.Template('http://epg.tvsou.com/program/tv_237/channel_1052/w${week}.htm'),  # 宜春二套
    'f66458c9ebd429987ec3e5cb931379c8': string.Template('http://epg.tvsou.com/program/tv_237/channel_1053/w${week}.htm'),  # 宜春三套
    '9291c40ec1cec1281638720c74c7245f': string.Template('http://epg.tvsou.com/program/tv_14/channel_36/w${week}.htm'),  # 辽宁卫视
    '88d167dd729a8aee0e22a5f996e445e4': string.Template('http://epg.tvsou.com/program/tv_14/channel_262/w${week}.htm'),  # 辽宁综合频道
    '0dd7fcaa1ee3134112f8bdc31f1038fb': string.Template('http://epg.tvsou.com/program/tv_14/channel_263/w${week}.htm'),  # 辽宁影视频道
    '185b9c0d8d3cc8d4bbfc5df72f166187': string.Template('http://epg.tvsou.com/program/tv_14/channel_264/w${week}.htm'),  # 辽宁娱乐频道
    'fa3e29cc341e0bb140f53f03cc7b274f': string.Template('http://epg.tvsou.com/program/tv_14/channel_265/w${week}.htm'),  # 辽宁青少频道
    '04143831a3cd2088b5b2f4605a44702e': string.Template('http://epg.tvsou.com/program/tv_14/channel_266/w${week}.htm'),  # 辽宁生活频道
    '62c2cca5d9dd0ec059ccde98f473cc79': string.Template('http://epg.tvsou.com/program/tv_74/channel_297/w${week}.htm'),  # 沈阳新闻频道
    'f571536704cd5c3652a82970d9665f5e': string.Template('http://epg.tvsou.com/program/tv_74/channel_298/w${week}.htm'),  # 沈阳综合频道
    '6761fd8ddb39fa15ceaa7416ed066528': string.Template('http://epg.tvsou.com/program/tv_74/channel_299/w${week}.htm'),  # 沈阳影视频道
    'bf6170af1393761634b9d2563d999f6c': string.Template('http://epg.tvsou.com/program/tv_74/channel_300/w${week}.htm'),  # 沈阳生活频道
    '1b541c526af2c651f0854d6f0d9fb357': string.Template('http://epg.tvsou.com/program/tv_74/channel_301/w${week}.htm'),  # 沈阳体育频道
    '90ba1d9fb74ccf1a988ad4d63c0a2201': string.Template('http://epg.tvsou.com/program/tv_75/channel_302/w${week}.htm'),  # 大连新闻综合频道
    '2a7b63b54db01697a112e7c3ac2eebc9': string.Template('http://epg.tvsou.com/program/tv_75/channel_303/w${week}.htm'),  # 大连生活频道
    '87ee84630e82bb2521ea08c3b2fadd23': string.Template('http://epg.tvsou.com/program/tv_75/channel_304/w${week}.htm'),  # 大连法制频道
    '2137e1db5db589d2d8a1d40fd3b2c0dd': string.Template('http://epg.tvsou.com/program/tv_75/channel_305/w${week}.htm'),  # 大连文体频道
    '1905f4890db6cb3eb198ef7513b11bbf': string.Template('http://epg.tvsou.com/program/tv_75/channel_306/w${week}.htm'),  # 大连影视频道
    '4ba4c23527479ffd0cbf0c68ef206a23': string.Template('http://epg.tvsou.com/program/tv_75/channel_1002/w${week}.htm'),  # 大连少儿频道
    '9a27e63742f85b839e6f4ab1a4dc5148': string.Template('http://epg.tvsou.com/program/tv_115/channel_595/w${week}.htm'),  # 抚顺影视频道
    '696a5312826f7ed5751bc684eb4e94e9': string.Template('http://epg.tvsou.com/program/tv_115/channel_596/w${week}.htm'),  # 抚顺公用频道
    '085e940db13b98b3807c8dc0983f98ab': string.Template('http://epg.tvsou.com/program/tv_115/channel_597/w${week}.htm'),  # 抚顺综合频道
    'b8d6ae077b2358859dcf5c6ce50168a5': string.Template('http://epg.tvsou.com/program/tv_142/channel_718/w${week}.htm'),  # 丹东一套
    '6f6594830b9fdc4fa705397ba290a012': string.Template('http://epg.tvsou.com/program/tv_142/channel_719/w${week}.htm'),  # 丹东二套
    'bbb88d962cec453d7a6b069b50890024': string.Template('http://epg.tvsou.com/program/tv_142/channel_720/w${week}.htm'),  # 丹东三套
    'a9021c457424addde64a2782bab7895c': string.Template('http://epg.tvsou.com/program/tv_164/channel_770/w${week}.htm'),  # 本溪一套
    '0638ecb0ae65295f815c869bf37349fb': string.Template('http://epg.tvsou.com/program/tv_164/channel_771/w${week}.htm'),  # 本溪二套
    'fbe488a7770021503d591bd6d03dfbd2': string.Template('http://epg.tvsou.com/program/tv_164/channel_772/w${week}.htm'),  # 本溪三套
    '9f25eeb266f0e7863f696e945f7ea27a': string.Template('http://epg.tvsou.com/program/tv_187/channel_822/w${week}.htm'),  # 铁岭一套
    '5389c031b37e11fd7709d945a90f235c': string.Template('http://epg.tvsou.com/program/tv_187/channel_823/w${week}.htm'),  # 铁岭二套
    'e06cb32274869abd8ea3db94dd248acf': string.Template('http://epg.tvsou.com/program/tv_187/channel_824/w${week}.htm'),  # 铁岭三套
    'c8038691774ef869aca6aeba78b652cd': string.Template('http://epg.tvsou.com/program/tv_204/channel_896/w${week}.htm'),  # 锦视综合频道
    '581b48ca69c36f52b574bcda45958e73': string.Template('http://epg.tvsou.com/program/tv_204/channel_897/w${week}.htm'),  # 锦视影视频道
    'c5b5e9d28dc303f0afe324f6bad5724b': string.Template('http://epg.tvsou.com/program/tv_204/channel_898/w${week}.htm'),  # 锦视生活频道
    '03295de404257fa9653b89bf2d0e47ac': string.Template('http://epg.tvsou.com/program/tv_34/channel_56/w${week}.htm'),  # 内蒙古卫视
    'd42a03c2008b7669818c767621cabb1d': string.Template('http://epg.tvsou.com/program/tv_34/channel_570/w${week}.htm'),  # 内蒙古新闻综合频道
    'c69cee5506636ff2136bbb667c93f81c': string.Template('http://epg.tvsou.com/program/tv_34/channel_571/w${week}.htm'),  # 内蒙古经济生活频道
    '526fde3c03a2c862d700551b67620796': string.Template('http://epg.tvsou.com/program/tv_34/channel_572/w${week}.htm'),  # 内蒙古文体娱乐频道
    '50a4250c056726f375bd2e2df672c559': string.Template('http://epg.tvsou.com/program/tv_34/channel_573/w${week}.htm'),  # 内蒙古影视剧频道
    '98166347d131792aa40d1206664e3bae': string.Template('http://epg.tvsou.com/program/tv_34/channel_574/w${week}.htm'),  # 内蒙古少儿频道
    '1cbdd1e125f9a7778d4716592d9e4088': string.Template('http://epg.tvsou.com/program/tv_34/channel_1015/w${week}.htm'),  # 内蒙古蒙语卫视
    '00a1bf15fff615b26be6e8d5acdc40ca': string.Template('http://epg.tvsou.com/program/tv_134/channel_692/w${week}.htm'),  # 呼和浩特新闻频道
    '2bc1ac05cd73f3a38a3385fab50bf565': string.Template('http://epg.tvsou.com/program/tv_134/channel_693/w${week}.htm'),  # 呼和浩特都市频道
    '0f6d1d50242dba257d6c845027d6499c': string.Template('http://epg.tvsou.com/program/tv_134/channel_694/w${week}.htm'),  # 呼和浩特影视频道
    'a09ab19928a6b2bd616f7e2eba1056ee': string.Template('http://epg.tvsou.com/program/tv_31/channel_53/w${week}.htm'),  # 宁夏卫视
    '0a6ccd528d9fb910f545867c2f4e7464': string.Template('http://epg.tvsou.com/program/tv_31/channel_438/w${week}.htm'),  # 宁夏公共频道
    '25399efed1f960327967302afa0a6082': string.Template('http://epg.tvsou.com/program/tv_31/channel_439/w${week}.htm'),  # 宁夏经济频道
    '7972385f546f2640d699634582e33873': string.Template('http://epg.tvsou.com/program/tv_31/channel_440/w${week}.htm'),  # 宁夏影视频道
    'a9cdb288de762ab0eeaf8177f12d93b3': string.Template('http://epg.tvsou.com/program/tv_168/channel_783/w${week}.htm'),  # 银川文体频道
    '9d6885bb5ca102c99463c624ca019b9f': string.Template('http://epg.tvsou.com/program/tv_168/channel_784/w${week}.htm'),  # 银川生活频道
    '137bc77208c0240792e5dc70ec2e8dd9': string.Template('http://epg.tvsou.com/program/tv_168/channel_785/w${week}.htm'),  # 银川公共频道
    '4ec095f1d2564f82341275fff64edb5a': string.Template('http://epg.tvsou.com/program/tv_114/channel_592/w${week}.htm'),  # 青海卫视
    'de004d66d97ac023a94efe6efae93a5e': string.Template('http://epg.tvsou.com/program/tv_114/channel_868/w${week}.htm'),  # 青海经济生活频道
    '34b3c7f03b0656631b7d9dc7d65e3690': string.Template('http://epg.tvsou.com/program/tv_114/channel_869/w${week}.htm'),  # 青海影视综艺频道
    'a644eac4bdeec30ba5ed9828e3f5b325': string.Template('http://epg.tvsou.com/program/tv_114/channel_870/w${week}.htm'),  # 青海综合频道
    '2d8c9241bc80c1c65694f9f8c170abcf': string.Template('http://epg.tvsou.com/program/tv_114/channel_871/w${week}.htm'),  # 青海付费数字电视
    '7eb238c4800509cb873e9f2f86f0d75e': string.Template('http://epg.tvsou.com/program/tv_198/channel_872/w${week}.htm'),  # 西宁新闻综合频道
    '64c79c3f4c9ef56fb1583a17151ae056': string.Template('http://epg.tvsou.com/program/tv_198/channel_873/w${week}.htm'),  # 西宁影视娱乐频道
    '28502a1b6bf5fbe7c6da9241db596237': string.Template('http://epg.tvsou.com/program/tv_19/channel_41/w${week}.htm'),  # 山东卫视
    '421a22d1a41b3e905abb483e01820a5b': string.Template('http://epg.tvsou.com/program/tv_19/channel_267/w${week}.htm'),  # 山东齐鲁频道
    '84650f858a6e2caed8b528153abbced7': string.Template('http://epg.tvsou.com/program/tv_19/channel_268/w${week}.htm'),  # 山东体育频道
    '14c41974b5d5a5c455446802b78d2796': string.Template('http://epg.tvsou.com/program/tv_19/channel_269/w${week}.htm'),  # 山东农科频道
    '486ba69b1ccd636c0cb33ee8075641c6': string.Template('http://epg.tvsou.com/program/tv_19/channel_270/w${week}.htm'),  # 山东公共频道
    '9e6070533d391620279c2911c1000f12': string.Template('http://epg.tvsou.com/program/tv_19/channel_485/w${week}.htm'),  # 山东综艺频道
    '3eaa200d033748c913d0692154fdd913': string.Template('http://epg.tvsou.com/program/tv_19/channel_486/w${week}.htm'),  # 山东影视频道
    'b859e0891d3fdf17afdc2435d2d78c6d': string.Template('http://epg.tvsou.com/program/tv_19/channel_487/w${week}.htm'),  # 山东生活频道
    'c6c5f466cc770fd5fac28ac940d355fd': string.Template('http://epg.tvsou.com/program/tv_19/channel_488/w${week}.htm'),  # 山东少儿频道
    '43fb20c340976e3ef659d5ac475f6a8a': string.Template('http://epg.tvsou.com/program/tv_89/channel_484/w${week}.htm'),  # 山东教育台
    '816b900d6e44f4e75828c1fb3a8387ce': string.Template('http://epg.tvsou.com/program/tv_71/channel_271/w${week}.htm'),  # 济南新闻综合频道
    '8b1476b623daf724061316754b9e0940': string.Template('http://epg.tvsou.com/program/tv_71/channel_272/w${week}.htm'),  # 济南都市频道
    'e7df17681517f165aa2c3203455f8d49': string.Template('http://epg.tvsou.com/program/tv_71/channel_273/w${week}.htm'),  # 济南影视频道
    '5efccc536f4ea328ed2ed8263612a897': string.Template('http://epg.tvsou.com/program/tv_71/channel_274/w${week}.htm'),  # 济南生活频道
    '2c07e0b96fa7f871d779545e276b0ed8': string.Template('http://epg.tvsou.com/program/tv_71/channel_275/w${week}.htm'),  # 济南少儿频道
    '9f88329b6c3ae3a54c4a37676bcb361e': string.Template('http://epg.tvsou.com/program/tv_71/channel_276/w${week}.htm'),  # 济南商务频道
    'e60fafc31705c1f03797bff13405723b': string.Template('http://epg.tvsou.com/program/tv_71/channel_590/w${week}.htm'),  # 济南娱乐频道
    '1c1bf77db7d3f8605b4749f99e2354d1': string.Template('http://epg.tvsou.com/program/tv_71/channel_591/w${week}.htm'),  # 济南教育频道
    'c88a48dcce629113a5ec5f41681f0ff2': string.Template('http://epg.tvsou.com/program/tv_72/channel_277/w${week}.htm'),  # 青岛新闻综合频道
    '8715c06e7b2e546202a298d3fa5bb3ff': string.Template('http://epg.tvsou.com/program/tv_72/channel_278/w${week}.htm'),  # 青岛生活服务频道
    '4c84c1e7c25da464649092abbd7af2dc': string.Template('http://epg.tvsou.com/program/tv_72/channel_279/w${week}.htm'),  # 青岛电视剧频道
    'ef70b5187a9864412cbe3fd8c4a6a678': string.Template('http://epg.tvsou.com/program/tv_72/channel_280/w${week}.htm'),  # 青岛文体频道
    'ea2058b0d8c0006497d1667e0fd95e14': string.Template('http://epg.tvsou.com/program/tv_72/channel_281/w${week}.htm'),  # 青岛公共频道
    '968fb389e27519f273de2f837cb6972a': string.Template('http://epg.tvsou.com/program/tv_72/channel_282/w${week}.htm'),  # 青岛影视频道
    '2f39eefe4c9a60ec757a4e29d7f676fe': string.Template('http://epg.tvsou.com/program/tv_88/channel_480/w${week}.htm'),  # 烟台新闻综合频道
    '5fe375b2630767e5d4d3f1d9ac9acb9e': string.Template('http://epg.tvsou.com/program/tv_88/channel_481/w${week}.htm'),  # 烟台经济生活频道
    '855376704bb5acb38c8c5ed4e3fa3d9c': string.Template('http://epg.tvsou.com/program/tv_88/channel_482/w${week}.htm'),  # 烟台影视频道
    '50abb0aa671ea1eb33dbb232876119ca': string.Template('http://epg.tvsou.com/program/tv_88/channel_483/w${week}.htm'),  # 烟台都市文体频道
    '4d61f5332525b0897cf20d442e2d9ad7': string.Template('http://epg.tvsou.com/program/tv_88/channel_1003/w${week}.htm'),  # 烟台电影频道
    'e98c3f060293fcae3b87b96276385859': string.Template('http://epg.tvsou.com/program/tv_105/channel_553/w${week}.htm'),  # 淄博新闻综合频道
    '0723814a03894fcd95d45628dfae4e9b': string.Template('http://epg.tvsou.com/program/tv_105/channel_554/w${week}.htm'),  # 淄博影视频道
    '50b57a201095cd37c2763f2b3dc8aa56': string.Template('http://epg.tvsou.com/program/tv_105/channel_555/w${week}.htm'),  # 淄博生活频道
    '76b9e728455b77b6803e1aabc5c2d7b5': string.Template('http://epg.tvsou.com/program/tv_105/channel_556/w${week}.htm'),  # 淄博都市频道
    '643dada3e74a23f384c2db8583d2185b': string.Template('http://epg.tvsou.com/program/tv_105/channel_557/w${week}.htm'),  # 淄博科教频道
    '3a24a671669b073683eb6a0736041e43': string.Template('http://epg.tvsou.com/program/tv_105/channel_1041/w${week}.htm'),  # 张店电视台
    '3f547ac366ff7d1e86a3dd660c8193d3': string.Template('http://epg.tvsou.com/program/tv_122/channel_630/w${week}.htm'),  # 潍坊新闻频道
    '7cfc4932022af5b645c8ea282c52e481': string.Template('http://epg.tvsou.com/program/tv_122/channel_631/w${week}.htm'),  # 潍坊影视频道
    '8ea535a5ed13fee65831cf0880211e9c': string.Template('http://epg.tvsou.com/program/tv_122/channel_632/w${week}.htm'),  # 潍坊法制生活频道
    'a99694ebddd4bb45637df1976156b067': string.Template('http://epg.tvsou.com/program/tv_122/channel_633/w${week}.htm'),  # 潍坊科教频道
    '4ab074401a744a45f4e68882d4007348': string.Template('http://epg.tvsou.com/program/tv_122/channel_634/w${week}.htm'),  # 潍坊有线CH-8频道
    '7092dc5eb8c022bf2c42b22df181cdeb': string.Template('http://epg.tvsou.com/program/tv_158/channel_752/w${week}.htm'),  # 临沂生活娱乐频道
    '5aa97ff47e6cd77768b73429413ec807': string.Template('http://epg.tvsou.com/program/tv_158/channel_753/w${week}.htm'),  # 临沂新闻综合
    '47d22020b68dbade6c9ea47fcfa58e0c': string.Template('http://epg.tvsou.com/program/tv_158/channel_754/w${week}.htm'),  # 临沂影视频道
    'ffc9fe0c3e463e0c7aa4feab52df556d': string.Template('http://epg.tvsou.com/program/tv_158/channel_755/w${week}.htm'),  # 临沂导视资讯频道
    '601750b02a50aa13ec46d1a1bbce0bcf': string.Template('http://epg.tvsou.com/program/tv_158/channel_786/w${week}.htm'),  # 临沂都市频道
    '78ad475be60b663aad98849b9d7b3aab': string.Template('http://epg.tvsou.com/program/tv_165/channel_773/w${week}.htm'),  # 东营一套
    'f308916bd598f1c5b59d87d9693b91bc': string.Template('http://epg.tvsou.com/program/tv_165/channel_774/w${week}.htm'),  # 东营二套
    '7adf96d0736681d2644869570db030d1': string.Template('http://epg.tvsou.com/program/tv_165/channel_775/w${week}.htm'),  # 东营三套
    '246ed5b94d4784d2ab2ef0562d90ac97': string.Template('http://epg.tvsou.com/program/tv_165/channel_776/w${week}.htm'),  # 东营教育台
    '150d36006acf71f264699d68372d4366': string.Template('http://epg.tvsou.com/program/tv_166/channel_777/w${week}.htm'),  # 胜利电视台一套
    '7962c69c5f18900c0eabb99f823aa3df': string.Template('http://epg.tvsou.com/program/tv_166/channel_778/w${week}.htm'),  # 胜利电视台二套
    '6dd2d76049b6557983665447391de94a': string.Template('http://epg.tvsou.com/program/tv_166/channel_779/w${week}.htm'),  # 胜利电视台三套
    '15412fd15a0a5475da557b2c77409344': string.Template('http://epg.tvsou.com/program/tv_170/channel_790/w${week}.htm'),  # 日照综合频道
    '109478f630c9c3f5f0581b2c3f636587': string.Template('http://epg.tvsou.com/program/tv_170/channel_791/w${week}.htm'),  # 日照科教频道
    '045026a2f4bb15bf8eeb7fb2ef3b176d': string.Template('http://epg.tvsou.com/program/tv_170/channel_792/w${week}.htm'),  # 日照公共频道
    'a8d0ed99008b42dc7b57371a28b1b117': string.Template('http://epg.tvsou.com/program/tv_170/channel_793/w${week}.htm'),  # 日照影视频道
    'c985c856ca9661fd9da5321d65c593c3': string.Template('http://epg.tvsou.com/program/tv_190/channel_829/w${week}.htm'),  # 滨州一套
    'a90d0e36b6e7ef9d1233ad8829b8645c': string.Template('http://epg.tvsou.com/program/tv_190/channel_830/w${week}.htm'),  # 滨州有线频道
    'd5cde911b4541c07555812e9a37deae0': string.Template('http://epg.tvsou.com/program/tv_190/channel_831/w${week}.htm'),  # 滨州影视频道
    '46f2ec957d5a5546a58e82eaa404ad03': string.Template('http://epg.tvsou.com/program/tv_190/channel_832/w${week}.htm'),  # 滨州综艺频道
    'd9cb7713d82df81be52754d9ed330199': string.Template('http://epg.tvsou.com/program/tv_194/channel_844/w${week}.htm'),  # 济宁台一套
    '189664fef6d33138c56637b0703d8a10': string.Template('http://epg.tvsou.com/program/tv_194/channel_845/w${week}.htm'),  # 济宁台二套
    'a8d18ced674b66e8dbf0f848d5bb3f76': string.Template('http://epg.tvsou.com/program/tv_194/channel_846/w${week}.htm'),  # 济宁公共频道
    'af7784889cca468eec1745ddecceddbe': string.Template('http://epg.tvsou.com/program/tv_194/channel_847/w${week}.htm'),  # 山东公共济宁任城台
    'e298ccd6e6de44515d40815464838cc9': string.Template('http://epg.tvsou.com/program/tv_194/channel_848/w${week}.htm'),  # 山东公共济宁中区台
    'f93b6b85e3e605710135840bd4229f49': string.Template('http://epg.tvsou.com/program/tv_199/channel_874/w${week}.htm'),  # 泰安1套
    'ef9b33e7c64d27d43f542e0125d7e403': string.Template('http://epg.tvsou.com/program/tv_199/channel_875/w${week}.htm'),  # 泰安2套
    'ee0ed3dbb5abfaad00b59ed16fc42840': string.Template('http://epg.tvsou.com/program/tv_199/channel_876/w${week}.htm'),  # 泰安3套
    '65192497b96bcc53ccbf7fddc2ad33f0': string.Template('http://epg.tvsou.com/program/tv_199/channel_877/w${week}.htm'),  # 泰安4套
    '164a63e0a5ca867f9cae4914d3e2ac8a': string.Template('http://epg.tvsou.com/program/tv_199/channel_878/w${week}.htm'),  # 泰安电视频道
    'f524b3b587410413190cc03d7cf10532': string.Template('http://epg.tvsou.com/program/tv_203/channel_891/w${week}.htm'),  # 聊城电视台
    '116a14a681317fe96de0416659849555': string.Template('http://epg.tvsou.com/program/tv_203/channel_892/w${week}.htm'),  # 聊城电视台第六频道
    'b688a11fbecfafd9d919d2892ae22e12': string.Template('http://epg.tvsou.com/program/tv_203/channel_893/w${week}.htm'),  # 聊城电视台公共频道
    '7745616aee1e6520d6283cb666abeeed': string.Template('http://epg.tvsou.com/program/tv_203/channel_894/w${week}.htm'),  # 聊城电视台互动频道
    '3135a28bc946f75c04f04edde3ff49dd': string.Template('http://epg.tvsou.com/program/tv_203/channel_895/w${week}.htm'),  # 聊城电视台生活频道
    'c3cb62d9b3daa9f0dc715913e0ffc5b2': string.Template('http://epg.tvsou.com/program/tv_218/channel_967/w${week}.htm'),  # 威海一套
    '19085ec5480f2ffaf0a28a14a033e0f9': string.Template('http://epg.tvsou.com/program/tv_218/channel_968/w${week}.htm'),  # 威海二套
    '47389f5fd95c51d4c3531b707341603f': string.Template('http://epg.tvsou.com/program/tv_218/channel_969/w${week}.htm'),  # 威海三套
    '73897392e87e9eba689006af33e4c3a3': string.Template('http://epg.tvsou.com/program/tv_218/channel_970/w${week}.htm'),  # 环翠台
    '520e83fd72719ebbb32b5e487570ff52': string.Template('http://epg.tvsou.com/program/tv_225/channel_991/w${week}.htm'),  # 德州电视综合频道
    '52a610da9cf2e7f91c51c7a191b5ce41': string.Template('http://epg.tvsou.com/program/tv_225/channel_992/w${week}.htm'),  # 德州电视图文频道
    'd693a55e5cf12bea1f08e307135d3b3f': string.Template('http://epg.tvsou.com/program/tv_225/channel_993/w${week}.htm'),  # 德州电视影视频道
    '91d710a52c93c0df99b3749d7bd8fbef': string.Template('http://epg.tvsou.com/program/tv_229/channel_1027/w${week}.htm'),  # 菏泽电视台一套
    '6c76c1b0c0f0bb03b088fc27af7ff4e6': string.Template('http://epg.tvsou.com/program/tv_229/channel_1028/w${week}.htm'),  # 菏泽电视台二套
    '3bc3c5793a91715af4938cf5c9cebe3d': string.Template('http://epg.tvsou.com/program/tv_229/channel_1029/w${week}.htm'),  # 菏泽电视台三套
    'c768d67bd4578f74f4f4beeb93cb04ed': string.Template('http://epg.tvsou.com/program/tv_230/channel_1030/w${week}.htm'),  # 枣庄电视台一套
    '66ad3751eaeb26cda722d0d83fd7f909': string.Template('http://epg.tvsou.com/program/tv_230/channel_1031/w${week}.htm'),  # 枣庄电视台二套
    'f9e7c4fc81a7aa54f7163ee6c0639f09': string.Template('http://epg.tvsou.com/program/tv_230/channel_1032/w${week}.htm'),  # 枣庄电视台三套
    '2aeb585ccaca9fa893b0bdfdbc098c7f': string.Template('http://epg.tvsou.com/program/tv_17/channel_39/w${week}.htm'),  # 山西卫视
    '37fda6d3c2e6de96d71bac2fcf25dac6': string.Template('http://epg.tvsou.com/program/tv_17/channel_228/w${week}.htm'),  # 山西新闻综合频道
    '7232a88f1eb67624fa64f73625f1cb93': string.Template('http://epg.tvsou.com/program/tv_17/channel_229/w${week}.htm'),  # 山西科教频道
    'dbafbe288c28c7b77d5f73b95e5488ce': string.Template('http://epg.tvsou.com/program/tv_17/channel_230/w${week}.htm'),  # 山西影视频道
    'c12236f33097084f33ff77e99a8e08fd': string.Template('http://epg.tvsou.com/program/tv_17/channel_446/w${week}.htm'),  # 山西公共频道
    'c5d52bcb819534bc1a853cc7b330fc48': string.Template('http://epg.tvsou.com/program/tv_85/channel_447/w${week}.htm'),  # 太原新闻频道
    '6544dc2a1a7318282bad9ae8e6b70f1b': string.Template('http://epg.tvsou.com/program/tv_85/channel_448/w${week}.htm'),  # 太原百姓频道
    '1c4b5ce933955adc87ff004afa470c17': string.Template('http://epg.tvsou.com/program/tv_85/channel_449/w${week}.htm'),  # 太原法制频道
    '7cf438382e282d7ae8e0cac022bac64e': string.Template('http://epg.tvsou.com/program/tv_85/channel_450/w${week}.htm'),  # 太原影视频道
    '6a8fec6c8873b5e6e77c8d2bafd32027': string.Template('http://epg.tvsou.com/program/tv_85/channel_451/w${week}.htm'),  # 太原文体频道
    '1f9dd65c421ad7b9aea9ffefcf4126f8': string.Template('http://epg.tvsou.com/program/tv_85/channel_1008/w${week}.htm'),  # 太原教育台
    '65d31d4c1cfca2b5a143b953d73e7f23': string.Template('http://epg.tvsou.com/program/tv_140/channel_713/w${week}.htm'),  # 晋中新闻综合
    'fc5bb7196154250f66305177d685b978': string.Template('http://epg.tvsou.com/program/tv_140/channel_714/w${week}.htm'),  # 晋中经济生活
    '3608fd23aac49bb188765ef7353abcfa': string.Template('http://epg.tvsou.com/program/tv_159/channel_756/w${week}.htm'),  # 临汾一套
    '62bd6455e6f848acd38bd78688ec3510': string.Template('http://epg.tvsou.com/program/tv_159/channel_757/w${week}.htm'),  # 临汾二套
    '36f6ac9dde340dbb0aa14c164c72bc3b': string.Template('http://epg.tvsou.com/program/tv_159/channel_838/w${week}.htm'),  # 临汾三套
    '8ce495fb4679c93f016dcaae135301a6': string.Template('http://epg.tvsou.com/program/tv_161/channel_762/w${week}.htm'),  # 黄河电视台
    '883b3c380179bc13ce29b9140d89e214': string.Template('http://epg.tvsou.com/program/tv_197/channel_855/w${week}.htm'),  # 大同新闻综合频道
    '3724e77f0ba42c61ca0e68d3d16d4341': string.Template('http://epg.tvsou.com/program/tv_197/channel_856/w${week}.htm'),  # 大同影视频道
    'd2ff8a162366974788ef8840514a6f0d': string.Template('http://epg.tvsou.com/program/tv_197/channel_857/w${week}.htm'),  # 大同生活频道
    'eb7330e363ceec8c6895eacc44a1a804': string.Template('http://epg.tvsou.com/program/tv_18/channel_40/w${week}.htm'),  # 陕西卫视
    '74b1cb2dbf7849ffca68586161f68e7b': string.Template('http://epg.tvsou.com/program/tv_18/channel_314/w${week}.htm'),  # 陕西新闻综合频道
    'c4505088b470cd2b636e002f827a9478': string.Template('http://epg.tvsou.com/program/tv_18/channel_315/w${week}.htm'),  # 陕西都市青春频道
    '7a0ef1c4eefae2651e344e776d7494b2': string.Template('http://epg.tvsou.com/program/tv_18/channel_1407/w${week}.htm'),  # 陕西体育健康频道
    'da64c5961d4cf1e92313d4005d41f63a': string.Template('http://epg.tvsou.com/program/tv_18/channel_355/w${week}.htm'),  # 陕西经济资讯频道
    '68cb1eca2f3b03759e4ffaa1958a36f9': string.Template('http://epg.tvsou.com/program/tv_18/channel_356/w${week}.htm'),  # 陕西影视娱乐频道
    '07c91539bd4de12bbfd6a241ebc4d4f4': string.Template('http://epg.tvsou.com/program/tv_18/channel_357/w${week}.htm'),  # 陕西公共政法频道
    '86c8a530f92f45590b129ef63008aa46': string.Template('http://epg.tvsou.com/program/tv_18/channel_1011/w${week}.htm'),  # 陕西电视购物
    '4bf75bae559465e87bf96eed8f9eafb1': string.Template('http://epg.tvsou.com/program/tv_63/channel_214/w${week}.htm'),  # 西安白鸽都市频道
    'ccb9126398d6d1d8dd1bda54d2201028': string.Template('http://epg.tvsou.com/program/tv_63/channel_215/w${week}.htm'),  # 西安新闻综合频道
    '941e8875f601fead423e7ac6baaf8dbf': string.Template('http://epg.tvsou.com/program/tv_63/channel_359/w${week}.htm'),  # 西安商务资讯频道
    'f17c888a83890ee30ebff50b3d3ae3fd': string.Template('http://epg.tvsou.com/program/tv_63/channel_360/w${week}.htm'),  # 西安文化影视频道
    'cbed74a1d5ca2ed125d16321abf569c4': string.Template('http://epg.tvsou.com/program/tv_63/channel_361/w${week}.htm'),  # 西安健康快乐频道
    '8af326e28f812dcb35a745b1c90cf9c3': string.Template('http://epg.tvsou.com/program/tv_63/channel_362/w${week}.htm'),  # 西安音乐综艺频道
    '74ca733eddc5c16163210a031f3295db': string.Template('http://epg.tvsou.com/program/tv_106/channel_558/w${week}.htm'),  # 炫动卡通
    '4821a7f7b9f3793b6996b93a974ebc61': string.Template('http://epg.tvsou.com/program/tv_108/channel_505/w${week}.htm'),  # 东方电影频道
    '8c3cd672cf8359441ac4d3bc423ddc0c': string.Template('http://epg.tvsou.com/program/tv_109/channel_198/w${week}.htm'),  # 上海教育电视台
    '7d9661220f931bfc1f4b208e3efeee01': string.Template('http://epg.tvsou.com/program/tv_41/channel_82/w${week}.htm'),  # 上视新闻综合
    '7678dd13233dca41cef13ed97a706dc0': string.Template('http://epg.tvsou.com/program/tv_41/channel_83/w${week}.htm'),  # 上视第一财经
    '09c1add87829f698cd22e8f9dfc80c5c': string.Template('http://epg.tvsou.com/program/tv_41/channel_84/w${week}.htm'),  # 上视生活时尚
    '6d66325c27b7647781761f059c554a8c': string.Template('http://epg.tvsou.com/program/tv_41/channel_85/w${week}.htm'),  # 上视电视剧频道
    '4c67e8799a3c6455a26fadf7d672d320': string.Template('http://epg.tvsou.com/program/tv_41/channel_86/w${week}.htm'),  # 上视体育频道
    '8f8c1fce7cc7537a52056b0fd227679a': string.Template('http://epg.tvsou.com/program/tv_41/channel_87/w${week}.htm'),  # 上视纪实频道
    'b0624dfb3bd6bb4f345387d7092793b7': string.Template('http://epg.tvsou.com/program/tv_41/channel_88/w${week}.htm'),  # 东方新闻娱乐
    'f34d4d66567f85badd3b96830a66fe58': string.Template('http://epg.tvsou.com/program/tv_41/channel_89/w${week}.htm'),  # 东方文艺频道
    '9d0951906f3a9ff46fa04ef3fef41653': string.Template('http://epg.tvsou.com/program/tv_41/channel_90/w${week}.htm'),  # 东方音乐频道
    'b299409dd0d08b71e4a3a9eb88f54a98': string.Template('http://epg.tvsou.com/program/tv_41/channel_91/w${week}.htm'),  # 东方戏剧频道
    '3f5a1c61ad9aac44596de820572e78c0': string.Template('http://epg.tvsou.com/program/tv_41/channel_92/w${week}.htm'),  # 东方少儿频道
    'b73e58a5db1d1e991c639b9064c6736b': string.Template('http://epg.tvsou.com/program/tv_208/channel_908/w${week}.htm'),  # SiTV首映剧场
    '8c2c76bce805d11f5ba0266f8a33c65e': string.Template('http://epg.tvsou.com/program/tv_208/channel_909/w${week}.htm'),  # SiTV都市剧场
    '6442ecfd75afe2219a6298137b5cd3f0': string.Template('http://epg.tvsou.com/program/tv_208/channel_910/w${week}.htm'),  # SiTV白金剧场
    '1800444c032205d1443af46a5111fbf1': string.Template('http://epg.tvsou.com/program/tv_208/channel_911/w${week}.htm'),  # SiTV欢笑剧场
    '1cb2a982919d7a65a825ba986cdc68a0': string.Template('http://epg.tvsou.com/program/tv_208/channel_912/w${week}.htm'),  # SiTV经典剧场
    'a57bb859618877ab8cf2d2abf30b4f55': string.Template('http://epg.tvsou.com/program/tv_208/channel_913/w${week}.htm'),  # 游戏风云
    '5c8dbe3714f3544285a4c4922e2ed01a': string.Template('http://epg.tvsou.com/program/tv_208/channel_914/w${week}.htm'),  # 动漫秀场
    '17f79002aa3904b69fc41b463c04cca3': string.Template('http://epg.tvsou.com/program/tv_208/channel_915/w${week}.htm'),  # 卫生健康
    '2e79089eeb8dfeb99cd21296facd2025': string.Template('http://epg.tvsou.com/program/tv_208/channel_916/w${week}.htm'),  # 全纪实
    '21ede7c42c8ccfa34e875339454f6a9a': string.Template('http://epg.tvsou.com/program/tv_208/channel_917/w${week}.htm'),  # SiTV娱乐前线
    '05d6693c933de13842e71023eee86cdd': string.Template('http://epg.tvsou.com/program/tv_208/channel_918/w${week}.htm'),  # SiTV法制天地
    '8a29f3de1096334d5a784ebadf4895e3': string.Template('http://epg.tvsou.com/program/tv_208/channel_919/w${week}.htm'),  # SiTV七彩戏剧
    '2ac392f31cfbacdee4cb042d6bd4ad75': string.Template('http://epg.tvsou.com/program/tv_208/channel_920/w${week}.htm'),  # SiTV魅力音乐
    '50affb5cabcf6d7fbde1851d5e97a5e2': string.Template('http://epg.tvsou.com/program/tv_208/channel_921/w${week}.htm'),  # SiTV英语教室
    '931af08b8cf0f4d5cae1257b8e3365a3': string.Template('http://epg.tvsou.com/program/tv_208/channel_922/w${week}.htm'),  # SiTV学习考试
    'a4d72876a289825786845866024a4765': string.Template('http://epg.tvsou.com/program/tv_208/channel_923/w${week}.htm'),  # SiTV金色频道
    '6612405d22d72e43ac5dc9d1762c5109': string.Template('http://epg.tvsou.com/program/tv_208/channel_924/w${week}.htm'),  # SiTV极速汽车
    '0c387b6ead6bca8f1c6536c044d57a3c': string.Template('http://epg.tvsou.com/program/tv_208/channel_925/w${week}.htm'),  # SiTV幸福彩
    'ec17ed7955860104e3dd5d603c738c45': string.Template('http://epg.tvsou.com/program/tv_208/channel_926/w${week}.htm'),  # SiTV华夏影院
    '980195147b187b5f06632d605db9ec24': string.Template('http://epg.tvsou.com/program/tv_208/channel_927/w${week}.htm'),  # SiTV海外影院
    'a7269d232e819aed29e86494a7c74745': string.Template('http://epg.tvsou.com/program/tv_208/channel_928/w${week}.htm'),  # SiTV情感影院
    'bb82282ed6d91a977cb2f1ce48cb1be9': string.Template('http://epg.tvsou.com/program/tv_208/channel_929/w${week}.htm'),  # SiTV动作影院
    '5efeca7e022b70edc65113e1edfd00d8': string.Template('http://epg.tvsou.com/program/tv_208/channel_930/w${week}.htm'),  # SiTV怀旧影院
    '4363023e5df99c787053f5a682fc2bfc': string.Template('http://epg.tvsou.com/program/tv_208/channel_931/w${week}.htm'),  # SiTV点播影院
    'c98a9b505c44c32765353c1d71446886': string.Template('http://epg.tvsou.com/program/tv_208/channel_932/w${week}.htm'),  # SiTV探索精选
    '2ccef4b3a8b8f1686594ab6a8c3ba802': string.Template('http://epg.tvsou.com/program/tv_208/channel_933/w${week}.htm'),  # SiTV劲爆体育
    '0540abd135c14f05d31f6b27038f2724': string.Template('http://epg.tvsou.com/program/tv_208/channel_934/w${week}.htm'),  # SiTV劲爆足球
    '38bb74f00b940f4e4471c09e88c306bf': string.Template('http://epg.tvsou.com/program/tv_208/channel_935/w${week}.htm'),  # SiTV五星体育
    'e481793f18920c539db6b9d20288d63f': string.Template('http://epg.tvsou.com/program/tv_208/channel_936/w${week}.htm'),  # SiTV爵士流行
    'd1e054df8ee29f914c1fd8f26b8d51d5': string.Template('http://epg.tvsou.com/program/tv_208/channel_937/w${week}.htm'),  # SiTV恢宏古典
    '00d237b6fac4b3ae52be299b6bb4f979': string.Template('http://epg.tvsou.com/program/tv_208/channel_938/w${week}.htm'),  # SiTV华夏乐韵
    '70748ad11b93cb44e82a829f736bc4f4': string.Template('http://epg.tvsou.com/program/tv_208/channel_939/w${week}.htm'),  # SiTV轻松节拍
    'f567e2ed305e7b15674e99eb86dc66d0': string.Template('http://epg.tvsou.com/program/tv_208/channel_940/w${week}.htm'),  # SiTV英语世界
    'cf432e05aba8a3d64d92cb59feca9f9b': string.Template('http://epg.tvsou.com/program/tv_208/channel_941/w${week}.htm'),  # SiTV写意古典
    'cf6b6a7abfcbd79cf221c0c7c4bcf20c': string.Template('http://epg.tvsou.com/program/tv_208/channel_942/w${week}.htm'),  # SiTV华语风云
    '4d1317030b8466a759ed5c8f58219f57': string.Template('http://epg.tvsou.com/program/tv_208/channel_943/w${week}.htm'),  # SiTV欧美劲爆
    '47e672fee603b2962ea4664b3bd43077': string.Template('http://epg.tvsou.com/program/tv_208/channel_944/w${week}.htm'),  # SiTV日韩潮流
    'fb3441b3fbe789819fe38d5e4f06ecd5': string.Template('http://epg.tvsou.com/program/tv_208/channel_945/w${week}.htm'),  # SiTV动感地带
    'b82fa4086c9a2c9442279efbb80cce31': string.Template('http://epg.tvsou.com/program/tv_36/channel_58/w${week}.htm'),  # 四川卫视
    '103d89cc23f34f007b3f996538593ead': string.Template('http://epg.tvsou.com/program/tv_36/channel_158/w${week}.htm'),  # 四川文化旅游频道
    'f757e38f5d47eeafa84b6aee30eda8ea': string.Template('http://epg.tvsou.com/program/tv_36/channel_160/w${week}.htm'),  # 四川经济频道
    '6e6c5ad0cb54c26a512f2b70edffac85': string.Template('http://epg.tvsou.com/program/tv_36/channel_161/w${week}.htm'),  # 四川新闻资讯频道
    '4d6509eaf683a0c03283d996680aedf2': string.Template('http://epg.tvsou.com/program/tv_36/channel_162/w${week}.htm'),  # 四川影视文艺频道
    '0b8f9c8b3a28e52b38278f314ead0e9c': string.Template('http://epg.tvsou.com/program/tv_36/channel_164/w${week}.htm'),  # 四川妇女儿童频道
    '5bc74a87eaeeec792b98d2da229d31c6': string.Template('http://epg.tvsou.com/program/tv_36/channel_181/w${week}.htm'),  # 四川公共频道
    'd26a249566dd5b52a4b43040d222b4cb': string.Template('http://epg.tvsou.com/program/tv_36/channel_182/w${week}.htm'),  # 四川峨嵋电影频道
    '93c1ee04a8f20339742e3e0d7c19cb1d': string.Template('http://epg.tvsou.com/program/tv_36/channel_1603/w${week}.htm'),  # 四川科技教育
    'a7a94c745d65b93735eb5205c4c22ec2': string.Template('http://epg.tvsou.com/program/tv_54/channel_166/w${week}.htm'),  # 成都新闻综合频道
    '5409658f7847d87e302a138ceaadda29': string.Template('http://epg.tvsou.com/program/tv_54/channel_167/w${week}.htm'),  # 成都经济资讯频道
    'c11c6e9ce363413a4a6a238698bc17c2': string.Template('http://epg.tvsou.com/program/tv_54/channel_168/w${week}.htm'),  # 成都都市生活频道
    'e41c5818afcd38b558ec9516e292a54c': string.Template('http://epg.tvsou.com/program/tv_54/channel_169/w${week}.htm'),  # 成都影视文艺频道
    '044beb8aeae07a7237b55319709be29f': string.Template('http://epg.tvsou.com/program/tv_54/channel_170/w${week}.htm'),  # 成都公共频道
    'ed71fec23b1f918df9dd094b9b2f2827': string.Template('http://epg.tvsou.com/program/tv_169/channel_787/w${week}.htm'),  # 德阳新闻频道
    '4dfd73ff0c7b928c355ae636912e869e': string.Template('http://epg.tvsou.com/program/tv_169/channel_788/w${week}.htm'),  # 德阳公共频道
    'a35ebe4a7bdef8dcf2bc4ce3fe1ef117': string.Template('http://epg.tvsou.com/program/tv_169/channel_789/w${week}.htm'),  # 德阳影视频道
    '3d144c96eb3e844be21ee26fd646fdac': string.Template('http://epg.tvsou.com/program/tv_186/channel_820/w${week}.htm'),  # 凉山新闻综合频道
    '78d8712f05f234e72f8878020ad8af0f': string.Template('http://epg.tvsou.com/program/tv_186/channel_821/w${week}.htm'),  # 凉山公共频道
    '6dc5390329c06d1b7d58b35ddcca1f7c': string.Template('http://epg.tvsou.com/program/tv_216/channel_964/w${week}.htm'),  # 南充新闻频道
    'db9179beb12746b48d5eab98c92a083c': string.Template('http://epg.tvsou.com/program/tv_216/channel_965/w${week}.htm'),  # 南充都市频道
    'f9f06338584c7038323fa42813ce21ae': string.Template('http://epg.tvsou.com/program/tv_216/channel_966/w${week}.htm'),  # 南充影视频道
    'c73f35112e74ce77a8ecc4ddb5628bf2': string.Template('http://epg.tvsou.com/program/tv_68/channel_246/w${week}.htm'),  # 卫视电影台
    '49f125723313913558fcd66e867b6aa9': string.Template('http://epg.tvsou.com/program/tv_93/channel_496/w${week}.htm'),  # 东风卫视
    '097340edc231b5856c93a5f41a4e1108': string.Template('http://epg.tvsou.com/program/tv_68/channel_559/w${week}.htm'),  # 卫视中文台
    '51e0dd9e95c49c84bc07b0676083439e': string.Template('http://epg.tvsou.com/program/tv_68/channel_560/w${week}.htm'),  # Star Movies
    '2707c2cac77cfb24362ae6822456b81c': string.Template('http://epg.tvsou.com/program/tv_68/channel_687/w${week}.htm'),  # Star Movies(新加坡)
    'c177706113ddeebd13bc3512a299de01': string.Template('http://epg.tvsou.com/program/tv_110/channel_575/w${week}.htm'),  # 华视主频
    '5c19707e3b827ea2a65de049eedf9268': string.Template('http://epg.tvsou.com/program/tv_110/channel_576/w${week}.htm'),  # 华视IQ教育文化频道
    '0d29483c69620b27e4576019cb33207d': string.Template('http://epg.tvsou.com/program/tv_110/channel_577/w${week}.htm'),  # 华视EQ休闲频道
    '12a3d7914271a7d2281a382fe76629b2': string.Template('http://epg.tvsou.com/program/tv_111/channel_578/w${week}.htm'),  # 中视主频道
    '2aacf0cfc7d397a0562f65faf0a9cf45': string.Template('http://epg.tvsou.com/program/tv_111/channel_579/w${week}.htm'),  # 中视综艺台
    'a315144d5c81e77db2da7b8caf9f118a': string.Template('http://epg.tvsou.com/program/tv_111/channel_580/w${week}.htm'),  # 中视新闻台
    '811f6ad5ce3de8bd300b6d6ffc8c7e99': string.Template('http://epg.tvsou.com/program/tv_112/channel_581/w${week}.htm'),  # 台湾家庭台
    '37dab69069ba9c551d111bf21f9981ad': string.Template('http://epg.tvsou.com/program/tv_112/channel_583/w${week}.htm'),  # 台湾国际台
    'f5e95ffb178dfb8386a29f188155c8c1': string.Template('http://epg.tvsou.com/program/tv_112/channel_584/w${week}.htm'),  # 台湾直播台
    '34325181e40061bcd215d71dd262800d': string.Template('http://epg.tvsou.com/program/tv_112/channel_585/w${week}.htm'),  # 台湾台
    '52d5cedf82b700354f699677b649e564': string.Template('http://epg.tvsou.com/program/tv_113/channel_586/w${week}.htm'),  # 东森综合台
    '404c047310e78077b398d2b5999e8459': string.Template('http://epg.tvsou.com/program/tv_113/channel_617/w${week}.htm'),  # 东森洋片
    '50c24d98ec6280f31256abf1ed23ca4a': string.Template('http://epg.tvsou.com/program/tv_113/channel_619/w${week}.htm'),  # 东森电影台
    'de7f979d7f9911da2ff827a417159a27': string.Template('http://epg.tvsou.com/program/tv_113/channel_621/w${week}.htm'),  # 东森戏剧台
    'bb1dd58bded54b9628a60db6bf42fc32': string.Template('http://epg.tvsou.com/program/tv_113/channel_622/w${week}.htm'),  # 东森娱乐频道
    '2b51289da8ea9bfde8be044179549419': string.Template('http://epg.tvsou.com/program/tv_113/channel_623/w${week}.htm'),  # 东森幼幼台
    '287cb9ad49a87d8f3d60d2eea995be43': string.Template('http://epg.tvsou.com/program/tv_113/channel_624/w${week}.htm'),  # 东森新闻台
    '8dfc506bcd2548ab510b4a1b0f5f848b': string.Template('http://epg.tvsou.com/program/tv_113/channel_625/w${week}.htm'),  # 东森亚洲卫视
    'b5a16d7d1d4bd49051dbd1b125e86114': string.Template('http://epg.tvsou.com/program/tv_118/channel_610/w${week}.htm'),  # 公视主频道
    '168e9996f33410551c3f38777f7e8780': string.Template('http://epg.tvsou.com/program/tv_119/channel_611/w${week}.htm'),  # 三立都会台
    'db8e1e5ca873263badcb5fe80cf52d5f': string.Template('http://epg.tvsou.com/program/tv_119/channel_612/w${week}.htm'),  # 三立新闻台
    'ab8296062e7e525ab1ff27a4c84547de': string.Template('http://epg.tvsou.com/program/tv_119/channel_613/w${week}.htm'),  # 三立台湾台
    '2f8a0d01bccc84dfa4cf9ba18557682a': string.Template('http://epg.tvsou.com/program/tv_120/channel_615/w${week}.htm'),  # 八大第一台
    '4c3a712dc806012bee7f6f94c8feb558': string.Template('http://epg.tvsou.com/program/tv_120/channel_616/w${week}.htm'),  # 八大综合台
    '1476762175b4e0d6f24cb9f7434610bb': string.Template('http://epg.tvsou.com/program/tv_120/channel_618/w${week}.htm'),  # 八大戏剧台
    '455473817406ed73cc2380985faa0089': string.Template('http://epg.tvsou.com/program/tv_121/channel_626/w${week}.htm'),  # TVBS
    '0c1d225ae4424a31fb269ca869d19a57': string.Template('http://epg.tvsou.com/program/tv_121/channel_627/w${week}.htm'),  # TVBS-G
    '2b1486b5fb7b188952e89eb08f66df37': string.Template('http://epg.tvsou.com/program/tv_121/channel_628/w${week}.htm'),  # TVBS-ASIA
    '5927c7a6dd31f38686fafa073e2e13bc': string.Template('http://epg.tvsou.com/program/tv_35/channel_57/w${week}.htm'),  # 天津卫视
    'd0a7326a8004189aac36ac8164d6003c': string.Template('http://epg.tvsou.com/program/tv_35/channel_151/w${week}.htm'),  # 天津经济生活频道
    '1cbc5eb94b8ff9f200518384e98cf27c': string.Template('http://epg.tvsou.com/program/tv_35/channel_152/w${week}.htm'),  # 天津文化娱乐频道
    '3f2ff97170fc53275b05750d80f4650d': string.Template('http://epg.tvsou.com/program/tv_35/channel_153/w${week}.htm'),  # 天津影视频道
    'b83d162831de409d447a9dba7b2e7641': string.Template('http://epg.tvsou.com/program/tv_35/channel_154/w${week}.htm'),  # 天津都市频道
    '7cb2013923bb4b90c1c49a3adba11aff': string.Template('http://epg.tvsou.com/program/tv_35/channel_155/w${week}.htm'),  # 天津体育频道
    '53c6655ae0527107b0fd84e2a9ae78a5': string.Template('http://epg.tvsou.com/program/tv_35/channel_156/w${week}.htm'),  # 天津科教频道
    '78efe3b9b396c48fa7f19c08535c166e': string.Template('http://epg.tvsou.com/program/tv_35/channel_157/w${week}.htm'),  # 天津少儿频道
    'c7e77d8421d3f520170e66523d83c4ac': string.Template('http://epg.tvsou.com/program/tv_35/channel_997/w${week}.htm'),  # 天津公共频道
    'feccf21eb7e50753355efdab2d54d9e8': string.Template('http://epg.tvsou.com/program/tv_32/channel_54/w${week}.htm'),  # 西藏卫视
    '68083aec8dafab3f5bd4665ce3ea98c2': string.Template('http://epg.tvsou.com/program/tv_32/channel_1017/w${week}.htm'),  # 西藏一套
    '03d1674376a4e346d69a2d566226f3cb': string.Template('http://epg.tvsou.com/program/tv_3/channel_22/w${week}.htm'),  # 本港台
    '4d48c50e455f23d653cffbbde36ef20c': string.Template('http://epg.tvsou.com/program/tv_3/channel_23/w${week}.htm'),  # 国际台
    'ca9b19519b657d524c001ef858181b5d': string.Template('http://epg.tvsou.com/program/tv_4/channel_24/w${week}.htm'),  # 翡翠台
    '9776f5ec6a90c7ee625610a141488c2e': string.Template('http://epg.tvsou.com/program/tv_4/channel_25/w${week}.htm'),  # 明珠台
    '1b2b30a7b682d1cb24a5ccd28f5161f6': string.Template('http://epg.tvsou.com/program/tv_4/channel_183/w${week}.htm'),  # TVB8
    'bd8e186f72439d0769b994e10ff8ef89': string.Template('http://epg.tvsou.com/program/tv_4/channel_184/w${week}.htm'),  # 星河频道
    '18d93f17d1114121681b43fbe9f3d7f8': string.Template('http://epg.tvsou.com/program/tv_4/channel_564/w${week}.htm'),  # 无线剧集台
    'd5b2e76e2b18ef8acf14f06191d2a15d': string.Template('http://epg.tvsou.com/program/tv_4/channel_565/w${week}.htm'),  # 无线经典台
    '05a1c5947f140da5c899559e6212ad8c': string.Template('http://epg.tvsou.com/program/tv_4/channel_566/w${week}.htm'),  # 无线音乐台
    'b94fd4de2afca65ea463964c973c43a1': string.Template('http://epg.tvsou.com/program/tv_4/channel_567/w${week}.htm'),  # 无线新闻台
    '2c0d302b11a292e14c9f43f49125663b': string.Template('http://epg.tvsou.com/program/tv_4/channel_568/w${week}.htm'),  # 无线生活台
    '11d886d7cafbd79e71cb7d33e2f92458': string.Template('http://epg.tvsou.com/program/tv_4/channel_569/w${week}.htm'),  # 无线儿童台
    'bb8f7378ef7eefeea8ec82cf7e34f173': string.Template('http://epg.tvsou.com/program/tv_5/channel_26/w${week}.htm'),  # 凤凰卫视
    'b52ed95ecc9995cb7a418061040c740f': string.Template('http://epg.tvsou.com/program/tv_5/channel_27/w${week}.htm'),  # 凤凰资讯台
    'fd5e69184516f4e96a7f4d41e52b3bb0': string.Template('http://epg.tvsou.com/program/tv_5/channel_28/w${week}.htm'),  # 凤凰电影台
    'bc420cf8383e85e20fbd61ff9d072254': string.Template('http://epg.tvsou.com/program/tv_7/channel_30/w${week}.htm'),  # 星空卫视
    '9c5e8689731d852528b281807f12c472': string.Template('http://epg.tvsou.com/program/tv_59/channel_209/w${week}.htm'),  # 阳光卫视
    '13bec0dc1753b55d0fd019e674c85958': string.Template('http://epg.tvsou.com/program/tv_228/channel_1026/w${week}.htm'),  # 香港NOW
    '1c2175b47f7357571afac7dad4ae755f': string.Template('http://epg.tvsou.com/program/tv_70/channel_251/w${week}.htm'),  # 有线足球台
    '52faed1613876f3c5924250da2421c47': string.Template('http://epg.tvsou.com/program/tv_70/channel_252/w${week}.htm'),  # 有线体育台
    'ca31cdbd9cd561ac68562bec80da93b3': string.Template('http://epg.tvsou.com/program/tv_70/channel_253/w${week}.htm'),  # 英超台
    'b14334232801ecbb905f9572c05be111': string.Template('http://epg.tvsou.com/program/tv_70/channel_258/w${week}.htm'),  # 儿童台
    '1938a8e4a6a1dcf9855fe1a5322b0843': string.Template('http://epg.tvsou.com/program/tv_70/channel_259/w${week}.htm'),  # 娱乐台
    'ee8ccbfc9ba3271f54e2e7b65111328e': string.Template('http://epg.tvsou.com/program/tv_70/channel_287/w${week}.htm'),  # 财经资讯台
    '133a8db16a06779b241b2229008d2bb0': string.Template('http://epg.tvsou.com/program/tv_70/channel_289/w${week}.htm'),  # 有线A台
    '5ebc3441e2337134060fc1f511a9d412': string.Template('http://epg.tvsou.com/program/tv_70/channel_290/w${week}.htm'),  # 新知台
    '964ad2ecbd9149b705514c0c5a0cb7fa': string.Template('http://epg.tvsou.com/program/tv_70/channel_291/w${week}.htm'),  # 电影1台
    'd1048b2e5a5d8e5ed1c3c8792dae544a': string.Template('http://epg.tvsou.com/program/tv_70/channel_292/w${week}.htm'),  # 电影2台
    '7b378c5b86347dc94d8a4dcc93df5d5a': string.Template('http://epg.tvsou.com/program/tv_70/channel_293/w${week}.htm'),  # 荷里活影院(HMC)
    '2357adab5fb307f4285a0cf663931d0d': string.Template('http://epg.tvsou.com/program/tv_70/channel_605/w${week}.htm'),  # MGM亚洲电影频道
    'ac36a3696ec781ba88e1469447723040': string.Template('http://epg.tvsou.com/program/tv_70/channel_606/w${week}.htm'),  # NBA TV
    '5e8b0772e50e1cb3c8d2fc028be982bd': string.Template('http://epg.tvsou.com/program/tv_70/channel_607/w${week}.htm'),  # MTV中文频道
    'd0e98b31e26979df6f81fcc9548b1b2e': string.Template('http://epg.tvsou.com/program/tv_70/channel_608/w${week}.htm'),  # 动物星球频道
    '43151260943da1adc32057346a36c6fc': string.Template('http://epg.tvsou.com/program/tv_70/channel_609/w${week}.htm'),  # Fashion TV
    'ad291a233f1fd3f24332e41461798a25': string.Template('http://epg.tvsou.com/program/tv_33/channel_55/w${week}.htm'),  # 新疆卫视
    '5d5b32f51a544f8800bb17e7e06e0b5e': string.Template('http://epg.tvsou.com/program/tv_33/channel_542/w${week}.htm'),  # 新疆维语综合频道
    '27979b0625fb4b04f8e75774b5074889': string.Template('http://epg.tvsou.com/program/tv_33/channel_543/w${week}.htm'),  # 新疆哈萨克语频道
    '992eda0954d1e66edeb0108cec74996f': string.Template('http://epg.tvsou.com/program/tv_33/channel_544/w${week}.htm'),  # 新疆汉语综艺频道
    '6a218dca2c67a8f58bf9ddf6c395c185': string.Template('http://epg.tvsou.com/program/tv_33/channel_545/w${week}.htm'),  # 新疆维语综艺频道
    'ecab8aa90fab52f758d90f1678ef4ec8': string.Template('http://epg.tvsou.com/program/tv_33/channel_546/w${week}.htm'),  # 新疆汉语影视频道
    '351c09f7d3856d6278b33d4a83a223a0': string.Template('http://epg.tvsou.com/program/tv_33/channel_547/w${week}.htm'),  # 新疆汉语经济生活
    '3c5e39fee5c26df7e9b4c39799db731f': string.Template('http://epg.tvsou.com/program/tv_33/channel_548/w${week}.htm'),  # 新疆哈语综艺频道
    '53a300adff5df78cbd2ada2b099be46c': string.Template('http://epg.tvsou.com/program/tv_33/channel_549/w${week}.htm'),  # 新疆维语经济生活
    '66170e96c62afe00a4e8acb3028b8d47': string.Template('http://epg.tvsou.com/program/tv_33/channel_550/w${week}.htm'),  # 新疆体育健康频道
    'a131b27ae3ffcbe2ff4f80b030ccd38e': string.Template('http://epg.tvsou.com/program/tv_33/channel_551/w${week}.htm'),  # 新疆法制信息频道
    'ee5905e0f191954d6c0d6712c71b8501': string.Template('http://epg.tvsou.com/program/tv_33/channel_552/w${week}.htm'),  # 新疆少儿频道
    'ad77cc612a0dcef9c788efb61384883f': string.Template('http://epg.tvsou.com/program/tv_33/channel_1016/w${week}.htm'),  # 新疆教育台
    '16d3812f7a440cfbe6dbc1d9de3ebb31': string.Template('http://epg.tvsou.com/program/tv_193/channel_839/w${week}.htm'),  # 乌鲁木齐新闻频道
    '014005d0ad57eb95cd0667edeb988125': string.Template('http://epg.tvsou.com/program/tv_193/channel_840/w${week}.htm'),  # 乌鲁木齐影视频道
    'ed10480d4e1a230b608a47523ae582a7': string.Template('http://epg.tvsou.com/program/tv_193/channel_841/w${week}.htm'),  # 乌鲁木齐都市频道
    'a4190734ac8aa80ded3253051f234533': string.Template('http://epg.tvsou.com/program/tv_193/channel_842/w${week}.htm'),  # 乌鲁木齐旅游频道
    '9d08f36b3dad281377dc599b16180ee8': string.Template('http://epg.tvsou.com/program/tv_193/channel_843/w${week}.htm'),  # 乌鲁木齐女性频道
    'c786da29f0f5cc5973444e3ad49413a6': string.Template('http://epg.tvsou.com/program/tv_27/channel_49/w${week}.htm'),  # 云南卫视
    'fb7430068bc78467581021b35716be81': string.Template('http://epg.tvsou.com/program/tv_27/channel_441/w${week}.htm'),  # 云南二套
    '18069a9a3dbd5af506c7429addefef8b': string.Template('http://epg.tvsou.com/program/tv_27/channel_442/w${week}.htm'),  # 云南三套
    '06d7bc32009ed2496dccfc33f1ed39f7': string.Template('http://epg.tvsou.com/program/tv_27/channel_443/w${week}.htm'),  # 云南四套
    '933a31cc6d6a978abebefb0f05d05064': string.Template('http://epg.tvsou.com/program/tv_27/channel_444/w${week}.htm'),  # 云南五套
    '4a33fe349a712a58985b39b40e182021': string.Template('http://epg.tvsou.com/program/tv_27/channel_445/w${week}.htm'),  # 云南六套
    '9e9b5d62418c70f7f86ad3513806cd11': string.Template('http://epg.tvsou.com/program/tv_80/channel_370/w${week}.htm'),  # 昆明综合频道
    '0b4abef47aecca7f027b67e9ccd82c45': string.Template('http://epg.tvsou.com/program/tv_80/channel_371/w${week}.htm'),  # 昆明生活频道
    'fc2eb1e99cc98f5bc79a7ac7c9466522': string.Template('http://epg.tvsou.com/program/tv_80/channel_372/w${week}.htm'),  # 昆明文娱频道
    '6103c36f917eb6e54648b06a2327ef46': string.Template('http://epg.tvsou.com/program/tv_80/channel_373/w${week}.htm'),  # 昆明体育频道
    '840c861c9e7e8603f554abc59632ea8c': string.Template('http://epg.tvsou.com/program/tv_80/channel_374/w${week}.htm'),  # 昆明影视频道
    'bc8679479647fe7ee4c2b056f01bd376': string.Template('http://epg.tvsou.com/program/tv_80/channel_375/w${week}.htm'),  # 昆明新闻频道
    'ac1e6fe0031ca560d854c656a7fd31b7': string.Template('http://epg.tvsou.com/program/tv_182/channel_812/w${week}.htm'),  # 西双版纳一套
    '8405e13b240f3852d74a4b25f962fc39': string.Template('http://epg.tvsou.com/program/tv_182/channel_813/w${week}.htm'),  # 西双版纳二套
    '2631d463affea5c6ef41116648d435b2': string.Template('http://epg.tvsou.com/program/tv_188/channel_825/w${week}.htm'),  # 玉溪新闻频道
    '590e187a8799b1890175d25ec85ea352': string.Template('http://epg.tvsou.com/program/tv_21/channel_43/w${week}.htm'),  # 浙江卫视
    '334b84668be06cab42c8cf46279d9f8f': string.Template('http://epg.tvsou.com/program/tv_21/channel_180/w${week}.htm'),  # 浙江钱江都市频道
    'ea15cdb3937c38e4f34c8ecf157f53ee': string.Template('http://epg.tvsou.com/program/tv_21/channel_187/w${week}.htm'),  # 浙江民生休闲频道
    '8e866feeb8c168d9b59e1b764c5069aa': string.Template('http://epg.tvsou.com/program/tv_21/channel_190/w${week}.htm'),  # 浙江经视
    '7faee6cc2cdfa530a3294baf338452ba': string.Template('http://epg.tvsou.com/program/tv_21/channel_191/w${week}.htm'),  # 浙江教育科技频道
    '769201d876776875251104ed183432bd': string.Template('http://epg.tvsou.com/program/tv_21/channel_192/w${week}.htm'),  # 浙江影视娱乐频道
    '6da2dc888931532b23055ec2724f961d': string.Template('http://epg.tvsou.com/program/tv_21/channel_193/w${week}.htm'),  # 浙江公共频道
    '5a912a5ae818cd71b706c67c611ad715': string.Template('http://epg.tvsou.com/program/tv_21/channel_194/w${week}.htm'),  # 浙江少儿频道
    'aeab659095e8408f70d08c0fb8b49bac': string.Template('http://epg.tvsou.com/program/tv_62/channel_211/w${week}.htm'),  # 绍兴公共频道
    '4f8cde24044ad4ff1efaae9c2c95bb3d': string.Template('http://epg.tvsou.com/program/tv_62/channel_212/w${week}.htm'),  # 绍兴新闻综合频道
    'c6e0ca60c65062db95a9713014b7455d': string.Template('http://epg.tvsou.com/program/tv_64/channel_226/w${week}.htm'),  # 杭州少儿频道
    '95f90cd1c40eeeeb0630dcf161120ef7': string.Template('http://epg.tvsou.com/program/tv_64/channel_232/w${week}.htm'),  # 杭州综合频道
    'f9af35c84aa79b4f04624baee5009538': string.Template('http://epg.tvsou.com/program/tv_64/channel_233/w${week}.htm'),  # 杭州西湖明珠频道
    '3e96f219611d37ca536d8cd24dfb16a9': string.Template('http://epg.tvsou.com/program/tv_64/channel_234/w${week}.htm'),  # 杭州生活频道
    '4d1088861c0c7479353061ef606cf38d': string.Template('http://epg.tvsou.com/program/tv_64/channel_235/w${week}.htm'),  # 杭州影视频道
    '64383cd30cff30daa027ff067297899a': string.Template('http://epg.tvsou.com/program/tv_64/channel_1004/w${week}.htm'),  # 杭州导视频道
    '7beb3314d8bfce9680b70d1c073f2494': string.Template('http://epg.tvsou.com/program/tv_100/channel_521/w${week}.htm'),  # 温州新闻综合频道
    'c2734ac351ddf0eb36836d650476758a': string.Template('http://epg.tvsou.com/program/tv_100/channel_522/w${week}.htm'),  # 温州经济科教频道
    'a37c9ae6fb06ce269d27b5bbc3231141': string.Template('http://epg.tvsou.com/program/tv_100/channel_523/w${week}.htm'),  # 温州都市生活频道
    '4c6d8777c4938c81fbd00dca55b3a6ec': string.Template('http://epg.tvsou.com/program/tv_100/channel_524/w${week}.htm'),  # 温州影视娱乐频道
    '7d1a516eaa42d6c360b5ea5fb25dba3e': string.Template('http://epg.tvsou.com/program/tv_101/channel_525/w${week}.htm'),  # 宁波新闻综合频道
    '6883edd0a7e20adedc6c1b9565ed88c0': string.Template('http://epg.tvsou.com/program/tv_101/channel_526/w${week}.htm'),  # 宁波经济生活频道
    '3feb15acd3074a62f33b30588f829bd2': string.Template('http://epg.tvsou.com/program/tv_101/channel_527/w${week}.htm'),  # 宁波都市文体频道
    '0e63f6bf8fc15ac1b017524d45c91e1f': string.Template('http://epg.tvsou.com/program/tv_101/channel_528/w${week}.htm'),  # 宁波影视剧频道
    '3d689e1e7e34067bde9f5512a80849a8': string.Template('http://epg.tvsou.com/program/tv_101/channel_529/w${week}.htm'),  # 宁波少儿频道
    'e05ddfa8a334b5777e8c620e3413fd5d': string.Template('http://epg.tvsou.com/program/tv_132/channel_689/w${week}.htm'),  # 嘉兴新闻综合频道
    '1eb8bbb2d32df5306dfe1ba0bb12fcef': string.Template('http://epg.tvsou.com/program/tv_132/channel_690/w${week}.htm'),  # 嘉兴文化影视频道
    '07d7be756fcf0d09dbc25f546103143b': string.Template('http://epg.tvsou.com/program/tv_132/channel_691/w${week}.htm'),  # 嘉兴公共城市频道
    'faccffa4e4ca261ca276f5cfd48a9eb1': string.Template('http://epg.tvsou.com/program/tv_135/channel_695/w${week}.htm'),  # 金华新闻综合频道
    '751d139c714d6100b7ad38b586573669': string.Template('http://epg.tvsou.com/program/tv_135/channel_696/w${week}.htm'),  # 金华教育科技频道
    '6af0dcdff674af7e664d30695f69373b': string.Template('http://epg.tvsou.com/program/tv_135/channel_697/w${week}.htm'),  # 金华经济生活频道
    'cc0862d681dc96adeedd449241af6b4d': string.Template('http://epg.tvsou.com/program/tv_146/channel_731/w${week}.htm'),  # 舟山新闻综合频道
    '4870bf825288719331af7742fba9edd0': string.Template('http://epg.tvsou.com/program/tv_146/channel_732/w${week}.htm'),  # 舟山经济生活频道
    '862a39e1d8809bfe0637cfd6e0d61b0f': string.Template('http://epg.tvsou.com/program/tv_146/channel_733/w${week}.htm'),  # 舟山文艺影视频道
}

class SiteTvsou(SiteBase):

    img_str_cache = {}
    
#    def __init__(self, channel_code):
#        SiteBase.__init__(self, channel_code)

    def run(self):
        """开始抓取"""
        if tvsou_url.has_key(self.channel_code):
            url = tvsou_url[self.channel_code]
            for i in range(1, 8):
#                print 'Week: %s' % i
                week_url = url.substitute({'week': i})
                programs = self.week(week_url)

                for program in programs:
                    p = self.db.query('SELECT * FROM program WHERE \
                        `date` = %s AND `time` = %s AND `channel_code` = %s LIMIT 1',
                        (program['date'], program['stime'], self.channel_code)).fetchone()
                    if not p:
                        try:
                            self.db.query("INSERT INTO `program`(`date`, `time`, `channel_code`, `name`, `site`, `created_at`) \
                                VALUES (%s, %s, %s, %s,  'tvsou',  now());",
                                (program['date'], program['stime'], self.channel_code, program['title']))
                        except Exception, e:
                            print "SQL Error! URL: %s" % (self.__week_url)
#                print programs
#                break
        else:
            return False

    def week(self, url):
        """抓取单天数据"""
        self.__week_url = url
        content = self.get_content(url)
        content = content.decode('gb18030').encode('utf-8')

        self.html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8')

        try:
            # 提取节目单日期
            date = self.html_content.find('td', attrs={'width': '140'})
            date = date.string
            
            p = re.compile('(\d+).(\d+).(\d+).')
            date_groups = p.match(date)
            
            year, month, day = date_groups.groups()
#            date = '%s-%s-%s' % (date_groups.group(1), date_groups.group(2), date_groups.group(3))
        except Exception, e:
            print "Date Error! URL: %s" % (self.__week_url)
            sys.exit(1)
        
        list = self.html_content.findAll('div', attrs={'id': ['PMT1', 'PMT2']})

        programs = []
        tomorrow = False
        for l in list:
#            print l.getString()
#            s = l.find('div', attrs={'id': 'e2'})
            self.replace_img(l)
            stime = l.find('div', attrs={'id': 'e1'}).find('font')
            stime_str = ''.join(stime.contents)

            hour, minute = stime_str.split(':', 2)
            if int(hour) < 6 and tomorrow == False:
                month_last_day = calendar.monthrange(int(year), int(month))[1]  # 获取当前月最后一天
                if int(day) == month_last_day:
                    month = int(month) + 1
                    day = 1
                else:
                    day = int(day) + 1
                tomorrow = True
            
            s_title_element = l.find('div', attrs={'id': 'e2'})

            program_title = []
            for element in s_title_element:
                if isinstance(element, BeautifulSoup.NavigableString):
                    program_title.append(element)
                elif isinstance(element, BeautifulSoup.Tag):
                    if element.name == 'br':
                        break
                    for elt in element:
                        if isinstance(elt, BeautifulSoup.NavigableString):
                            if elt.string.endswith(u'剧情'):
                                continue
                            program_title.append(elt)
#                    print element.contents
#                    program_title.append(element.string)
            program_title = ''.join(program_title).strip()

            program = {"stime": stime_str,
                       "title": program_title,
                       "date": "%s-%s-%s" % (year, month, day)
                       }
            programs.append(program)

        return programs

    def replace_img(self, element):
        """替换内容中的图片为文字"""
        from TvProgramBot.pytesser import image_to_string
        
        images = element.findAll('img')
        if images:
            for image in images:
                image_src = image['src']
                if not image_src.startswith('http://'):
                    image_src = 'http://souepg.com' + image_src
                if self.img_str_cache.has_key(image_src):
                    img_str = self.img_str_cache[image_src]
                else:
                    image_data = StringIO(self.get_content(str(image_src)))
                    try:
                        image_p = Image.open(image_data)
                    except Exception, e:
                        print "Image Error! URL: %s, Image Src: %s, Error: %s, Image Data: %s" % (self.__week_url, image_src, e, image_data)
                    image_width, image_height = image_p.size

                    if image_width <= 10 and image_height <= 10:
                        image_width = 20
                        image_height = 20
                    image_resize = image_p.resize((image_width*2, image_height*2))

                    if image_src.startswith('http://epg.tvsou.com/imagesnum/'):
                        lang = 'eng'
                    else:
                        lang = 'song'
                    try:
                        img_str = image_to_string(image_resize, lang=lang).strip()
                    except Exception, e:
                        print "Tesser Error! URL: %s, image_url: %s" % (self.__week_url, image_src)
                        sys.exit(1)
                    # 替换识别错误的中英文
                    if img_str == "体青":
                        img_str = "体育"
                    elif img_str == "中固":
                        img_str = "中国"
                    elif img_str == "直苫番":
                        img_str = "直播"
                    elif img_str == "置苫番":
                        img_str = "重播"
                    elif img_str == "臭运":
                        img_str = "奥运"
                    elif img_str == "诀嚣":
                        img_str = "决赛"
                    elif img_str == "联苫番":
                        img_str = "联播"
                    elif img_str == "早向":
                        img_str = "早间"
                    elif img_str == "晚向":
                        img_str = "晚间"
                    elif img_str == "时向":
                        img_str = "时间"
                    elif img_str == "夭气":
                        img_str = "天气"
                    elif img_str == "夭夭":
                        img_str = "天天"
                    elif img_str == "牛向":
                        img_str = "午间"
                    elif img_str == "王重续剧":
                        img_str = "连续剧"
                    elif img_str == "jr]":
                        img_str = "0"
                    elif img_str == "-1":
                        img_str = "4"
                    elif img_str == "iii":
                        img_str = "5"
                    elif img_str == 'If!':
                        img_str = "6"
                    elif img_str == 'T':
                        img_str = "7"
                    elif img_str == 'EIEI':
                        img_str = "8"
                        
                    elif img_str == '' and image_src.startswith('http://epg.tvsou.com/imagesnum/'):
                        img_str = ':'
                    elif img_str == '' and image_src.startswith('http://souepg.com/fangzhuaqu/out/'):
                        img_str = '一'

                    img_str = img_str.decode('utf-8')
                    self.img_str_cache[image_src] = img_str
                image.replaceWith(img_str)

if __name__ == "__main__":
    print "Hello World"
