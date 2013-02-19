<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opLikePluginAddtagsTask
 *
 * @package    opLikePlugin
 * @subpackage task
 * @author     tatsuya ichikawa <ichikawa@tejimaya.com>
 */

class opLikePluginAddtagsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'opLikePlugin';
    $this->name             = 'addtags';
    $this->briefDescription = 'Addtags Command for "opLikePlugin".';
 
    $this->detailedDescription = <<<EOF
Use this command to addtags "opLikePlugin".
EOF;
  }
 
  protected function execute($arguments = array(), $options = array())
  {
    $pluginPath = 'plugins/';
    $targetPlugin = array(
      $pluginPath.'opLikePlugin/lib/task/opTimelinePlugin.sh' => 'opTimelinePlugin',
      $pluginPath.'opLikePlugin/lib/task/opDiaryPlugin.sh' => 'opDiaryPlugin',
      $pluginPath.'opLikePlugin/lib/task/opCommunityTopicPlugin.sh' => 'opCommunityTopicPlugin');

    $dirList = array();
    $fileList = scandir($pluginPath);
    foreach($fileList as $value) {
      if (is_dir($pluginPath . $value))
      {
        array_push($dirList, $value);
      }
    }

    foreach($dirList as $dir) {
      $shKey = array_search($dir, $targetPlugin);
      if ($shKey !== FALSE)
      {
        exec(mb_substr($shKey, 0, strlen($shKey)), $shOutput);
        foreach ($shOutput as $output)
        {
          echo $output."\n";
        }
      }
    }

    // execute ./symfomy cc
    $sfCacheClearTask = new sfCacheClearTask($this->dispatcher, $this->formatter);
    $sfCacheClearTask->run($arguments = array(), $options = array('type' => 'all'));
    $rootPath = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();
  }
}