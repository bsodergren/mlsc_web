<?php
/**
 *
 * MLS Script
 *
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$fileHeaderComment = <<<'EOF'

MLS Script

EOF;

$rules             = [
    // '@PhpCsFixer'                                             => true,
    // '@PSR12'                                                  => true,
    // '@PER-CS1.0' => true,
    // '@PER-CS1.0:risky' => true,

    // '@PHP71Migration' => true,
    // '@PHPUnit75Migration:risky' => true,
      '@Symfony'                                              => true,
      '@Symfony:risky'                                        => true,

    'statement_indentation'                                   => true,
    'method_argument_space'                                   => [
        'keep_multiple_spaces_after_comma' => false,
        'on_multiline'                     => 'ensure_single_line',
    ],

    'no_unused_imports'                                       => true,
    'ordered_imports'                                         => [
        'sort_algorithm'                                                           => 'length',
        'imports_order'                                                            => [
            'const',
            'class',
            'function',
        ],
    ],

    'single_space_after_construct'                            => true,
    'curly_braces_position'                                   => [
        'control_structures_opening_brace'          => 'next_line_unless_newline_at_signature_end',
        'anonymous_functions_opening_brace'         => 'next_line_unless_newline_at_signature_end',
        'allow_single_line_empty_anonymous_classes' => true,
        'allow_single_line_anonymous_functions'     => true,
    ],

    // 'protected_to_private'                                    => false,
    // 'native_constant_invocation'                              => ['strict' => false],
    // 'nullable_type_declaration_for_default_null_value'        => ['use_nullable_type_declaration' => false],
    // 'no_superfluous_phpdoc_tags'                              => ['remove_inheritdoc' => true],
    'phpdoc_add_missing_param_annotation'                     => true,

    'header_comment'                                          => [
        'header'       => $fileHeaderComment,
        'comment_type' => 'PHPDoc',
        'location'     => 'after_open',
        'separate'     => 'bottom',
    ],

    // 'modernize_strpos'                                        => true,
    // 'get_class_to_class_keyword'                              => true,
    'braces'                                                  => [
       'allow_single_line_closure'                   => true,
       'position_after_functions_and_oop_constructs' => 'same'],

    'binary_operator_spaces'                                  => [
        'operators' => [
            '=>'             => 'align_single_space_by_scope',
            '='              => 'align_single_space_by_scope',
                       '===' => 'align_single_space_minimal',
        ],
    ],
];

$finder            = Finder::create()
    ->in([
        __DIR__,
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return (new Config())
    ->setFinder($finder)
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
;
