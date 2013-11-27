<?php
    function getStamp($style) {
        if($style == 'tv') {
            return '电视剧';
        }elseif($style == 'movie') {
            return '电影';
        }elseif($style == 'nba_team') {
            return 'NBA-球队';
        }elseif($style == 'nba_palyer') {
            return 'NBA-球员';
        }elseif($style == 'nba_partition') {
            return 'NBA-分区';
        }elseif($style == 'nba_coalition') {
            return 'NBA-联盟';
        }elseif($style == 'fb_international') {
            return '足球球队';
        }elseif($style == 'football') {
            return '足球分类';
        }elseif($style == 'fb_player') {
            return '足球球员';
        } elseif ($style == 'lanmu') {
            return '栏目';
        }
    }
?>