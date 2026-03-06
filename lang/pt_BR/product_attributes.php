<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Product Attribute Labels
    |--------------------------------------------------------------------------
    |
    | Mapeamento de chaves de atributos de produtos para labels legíveis em
    | português. Usado nas views públicas para exibição na ficha técnica.
    | Keys ignoradas na exibição são definidas em ProductAttribute::IGNORED_KEYS.
    |
    */

    'colour'               => 'Cor',
    'color'                => 'Cor',
    'gender'               => 'Gênero',
    'size'                 => 'Tamanho',
    'size_type'            => 'Tipo de tamanho',
    'condition'            => 'Condição',
    'delivery_weight'      => 'Peso para entrega',
    'fashion_suitable_for' => 'Indicado para',
    'fashion_size'         => 'Tamanho (moda)',

    // Campos ignorados pela IGNORED_KEYS (listados aqui apenas como referência,
    // mas não serão exibidos na view pública)
    'in_stock'                       => 'Em estoque',
    'stock_quantity'                 => 'Quantidade em estoque',
    'custom_1'                       => 'Informação adicional 1',
    'custom_2'                       => 'Informação adicional 2',
    'custom_4'                       => 'Informação adicional 4',
    'custom_5'                       => 'Informação adicional 5',
    'custom_6'                       => 'Informação adicional 6',
    'custom_7'                       => 'Informação adicional 7',
    'custom_8'                       => 'Informação adicional 8',
    'merchant_product_id'            => 'Código do produto na loja',
    'merchant_product_category_path' => 'Categoria do produto',
    'product_GTIN'                   => 'GTIN',
    'installment'                    => 'Parcelamento',

];
