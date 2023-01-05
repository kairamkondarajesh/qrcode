<?php

namespace Drupal\qr_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\node\NodeInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "qrcode_block_basic",
 *   admin_label = @Translation("QR Code Basic Block"),
 *   category = "Custom"
 * )
 */
class QrCodeBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * QRGeneratorController constructor.
   *
   * @param array $configuration
   *   Plugin config.
   * @param string $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param mixed $routeMatch
   *   RouteMatch service instance.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, $routeMatch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $routeMatch;
  }

  /**
   * QRGeneratorController create.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   Plugin config.
   * @param string $plugin_id
   *   Plugin id.
   * @param mixed $plugin_definition
   *   Plugin definition.
   *
   * @return static
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
    $configuration,
    $plugin_id,
    $plugin_definition,
    $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $node = $this->routeMatch->getParameter('node');
    if ($node instanceof NodeInterface && $node->getType() == 'product') {
      if ($node->hasField('field_app_purchase_link')) {
        $purchase_link = $node->get('field_app_purchase_link')->getValue();
        $options = new QROptions([
          'version'             => 8,
          'outputType'          => QRCode::OUTPUT_IMAGE_PNG,
          'eccLevel'            => QRCode::ECC_H,
          'scale'               => 10,
          'imageBase64'         => TRUE,
          'bgColor'             => [200, 200, 200],
          'imageTransparent'    => FALSE,
          'drawCircularModules' => TRUE,
          'circleRadius'        => 0.4,
          'moduleValues' => [
            1536 => [0, 63, 255],
            6    => [233, 233, 233],
            5632 => [241, 28, 163],
            2560 => [233, 0, 233],
            10   => [233, 233, 233],
            3072 => [233, 0, 0],
            12   => [233, 233, 233],
            3584 => [67, 99, 84],
            14   => [233, 233, 233],
            4096 => [62, 174, 190],
            16   => [233, 233, 233],
            1024 => [0, 0, 0],
            4    => [233, 233, 233],
            512  => [0, 0, 0],
            8    => [233, 233, 233],
            18   => [233, 233, 233],
            20    => [233, 233, 233],
          ],
        ]);
        $im = (new QRCode($options))->render($purchase_link['0']['value']);

        $renderable = [
          '#theme' => 'qrcode_block',
          '#qr_code' => new FormattableMarkup('<img width="100" height="100" src="data::src"></img>', [':src' => $im]),
        ];
        return $renderable;
      }
    }
  }

}
