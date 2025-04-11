<?php

declare(strict_types=1);

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Hello routes.
 */
final class NodeController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    $nodeSTorage = $this->entityTypeManager()->getStorage('node');;
    $query = $nodeSTorage->getQuery();
    $query->accessCheck(FALSE);
    $query->pager(12);
    $nids = $query->execute();
    //dump($nids);
    $nodes = $nodeSTorage->loadMultiple($nids);
    $rows = [];
    foreach ($nodes as $node) {
      $rows[] = [
        'nid' => $node->id(),
        'title' => $node->getTitle(),
        'type' => $node->bundle(),
      ];
    }
    $headers = [
      $this->t('Id'),
      $this->t('Title'),
      $this->t('Type'),
    ];
    $build['content'] = [
      '#type' => 'table',
      '#rows' => $rows,
      '#header' => $headers,
    ];
    $build['pager'] = [
      '#type' => 'pager',
    ];
    $build['#cache']['tags'] = ['node_list'];

    return $build;
  }

}
