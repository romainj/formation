<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class ChannelsController extends ControllerBase {

  public function content() {
    $storage = $this->entityTypeManager()->getStorage('node');
    $query = $storage->getQuery();
    $query->condition('type', 'channel');
    $query->pager('5');
    $nids = $query->execute();
    $channels = $storage->loadMultiple($nids);
    //ksm($channels);

    $items = [];
    foreach ($channels as $channel) {
      $attributs = $channel->get('field_categorie')->getValue();
      $tids = array_map(function ($value) {
        return $value['target_id'];
      }, $attributs);
      $article_ids = $storage->getQuery()
        ->condition('type', 'article')
        ->condition('field_categorie', $tids , 'IN')
        ->execute();
      $articles = $storage->loadMultiple($article_ids);
      $display_article = '';
      foreach ($articles as $article) {
        $display_article .= $article->label();
      }
      $items[] = $channel->label() . $display_article;
    }
    return [
      '#theme' => 'item_list',
      '#list_type' => 'ol',
      '#items' => $items,
      ];
  }
}
