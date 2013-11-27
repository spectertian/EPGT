<form method="get" action="">
    选择模型：
              <select name="m">
                        <option value="all"<?php echo ('all' == $m) ? 'selected=selected' : ''?>>全部</option>
                        <option value="film"<?php echo ('film' == $m) ? 'selected=selected' : ''?>>电影</option>
                        <option value="teleplay"<?php echo ('teleplay' == $m) ? 'selected=selected' : ''?>>电视剧</option>
                        <option value="television"<?php echo ('television' == $m) ? 'selected=selected' : ''?>>栏目</option>
                        <option value="actor"<?php echo ('actor' == $m) ? 'selected=selected' : ''?>>艺人</option>
                        <option value="basketball_player"<?php echo ('basketball_player' == $m) ? 'selected=selected' : ''?>>篮球球员</option>
                        <option value="footerball_player"<?php echo ('footerball_player' == $m) ? 'selected=selected' : ''?>>足球球员</option>
                        <option value="basketball_team"<?php echo ('basketball_team' == $m) ? 'selected=selected' : ''?>>篮球球队</option>
                        <option value="footerball_team"<?php echo ('footerball_team' == $m) ? 'selected=selected' : ''?>>足球球队</option>
                        <option value="nba_team"<?php echo ('nba_team' == $m) ? 'selected=selected' : ''?>>NBA球队</option>
                    </select>
                   &nbsp;<input type="submit" value="过滤">
</form>