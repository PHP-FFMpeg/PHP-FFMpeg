<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* search_index.twig */
class __TwigTemplate_a66c022330ac3ef27c4e3129d7da80a68fa5818c6428c86408dd49e4d6ee7186 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $context["__internal_53c8f0bdfc006102fdcc2b247b3261cf41ce90b87a09595e8dce6d798574f996"] = $this->loadTemplate("macros.twig", "search_index.twig", 1)->unwrap();
        // line 4
        echo "var search_data = {
    'index': {
        'searchIndex': ";
        // line 6
        echo twig_jsonencode_filter($this->getAttribute(($context["index"] ?? $this->getContext($context, "index")), "searchIndex", [], "array"));
        echo ",
        'info': [";
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["index"] ?? $this->getContext($context, "index")), "info", [], "array"));
        $context['loop'] = [
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        ];
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 9
            echo "[";
            // line 10
            if ((1 == $this->getAttribute($context["item"], 0, [], "array"))) {
                // line 11
                echo twig_jsonencode_filter($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "shortname", []));
                echo ",";
                // line 12
                echo twig_jsonencode_filter($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "namespace", []));
                echo ",";
                // line 13
                echo twig_jsonencode_filter($this->env->getExtension('Sami\Renderer\TwigExtension')->pathForClass($context, $this->getAttribute($context["item"], 1, [], "array")));
                echo ",";
                // line 14
                echo twig_jsonencode_filter((($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "parent", [])) ? ((" < " . $this->getAttribute($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "parent", []), "shortname", []))) : ("")));
                echo ",";
                // line 15
                echo twig_jsonencode_filter($this->env->getExtension('Sami\Renderer\TwigExtension')->getSnippet($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "shortdesc", [])));
                echo ",";
                // line 16
                echo 1;
            } elseif ((2 == $this->getAttribute(            // line 17
$context["item"], 0, [], "array"))) {
                // line 18
                echo twig_jsonencode_filter((($this->getAttribute($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "class", []), "shortname", []) . "::") . $this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "name", [])));
                echo ",";
                // line 19
                echo twig_jsonencode_filter($this->getAttribute($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "class", []), "name", []));
                echo ",";
                // line 20
                echo twig_jsonencode_filter($this->env->getExtension('Sami\Renderer\TwigExtension')->pathForMethod($context, $this->getAttribute($context["item"], 1, [], "array")));
                echo ",";
                // line 21
                echo twig_jsonencode_filter($context["__internal_53c8f0bdfc006102fdcc2b247b3261cf41ce90b87a09595e8dce6d798574f996"]->getmethod_parameters_signature($this->getAttribute($context["item"], 1, [], "array")));
                echo ",";
                // line 22
                echo twig_jsonencode_filter($this->env->getExtension('Sami\Renderer\TwigExtension')->getSnippet($this->getAttribute($this->getAttribute($context["item"], 1, [], "array"), "shortdesc", [])));
                echo ",";
                // line 23
                echo 2;
            } elseif ((3 == $this->getAttribute(            // line 24
$context["item"], 0, [], "array"))) {
                // line 25
                echo twig_jsonencode_filter($this->getAttribute($context["item"], 1, [], "array"));
                echo ",";
                // line 26
                echo "\"\"";
                echo ",";
                // line 27
                echo twig_jsonencode_filter($this->env->getExtension('Sami\Renderer\TwigExtension')->pathForNamespace($context, $this->getAttribute($context["item"], 1, [], "array")));
                echo ",";
                // line 28
                echo "\"\"";
                echo ",";
                // line 29
                echo "\"\"";
                echo ",";
                // line 30
                echo 3;
            }
            // line 32
            echo "]";
            // line 33
            echo (($this->getAttribute($context["loop"], "last", [])) ? ("") : (","));
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 35
        echo "]
    }
}
search_data['index']['longSearchIndex'] = search_data['index']['searchIndex']";
    }

    public function getTemplateName()
    {
        return "search_index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  133 => 35,  119 => 33,  117 => 32,  114 => 30,  111 => 29,  108 => 28,  105 => 27,  102 => 26,  99 => 25,  97 => 24,  95 => 23,  92 => 22,  89 => 21,  86 => 20,  83 => 19,  80 => 18,  78 => 17,  76 => 16,  73 => 15,  70 => 14,  67 => 13,  64 => 12,  61 => 11,  59 => 10,  57 => 9,  40 => 8,  36 => 6,  32 => 4,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% from \"macros.twig\" import method_parameters_signature %}

{%- autoescape false -%}
var search_data = {
    'index': {
        'searchIndex': {{ index['searchIndex']|json_encode }},
        'info': [
            {%- for item in index['info'] -%}
                [
                    {%- if 1 == item[0] %}
                        {{- item[1].shortname|json_encode }},
                        {{- item[1].namespace|json_encode }},
                        {{- class_path(item[1])|json_encode }},
                        {{- (item[1].parent ? ' < ' ~ item[1].parent.shortname : '')|json_encode }},
                        {{- item[1].shortdesc|snippet|json_encode }},
                        {{- 1 }}
                    {%- elseif 2 == item[0] %}
                        {{- (item[1].class.shortname ~ '::' ~ item[1].name)|json_encode }},
                        {{- item[1].class.name|json_encode }},
                        {{- method_path(item[1])|json_encode }},
                        {{- method_parameters_signature(item[1])|json_encode }},
                        {{- item[1].shortdesc|snippet|json_encode }},
                        {{- 2 }}
                    {%- elseif 3 == item[0] %}
                        {{- item[1]|json_encode }},
                        {{- '\"\"' }},
                        {{- namespace_path(item[1])|json_encode }},
                        {{- '\"\"' }},
                        {{- '\"\"' }},
                        {{- 3 }}
                    {%- endif -%}
                ]
                {{- loop.last ? '' : ',' }}
            {%- endfor -%}
        ]
    }
}
search_data['index']['longSearchIndex'] = search_data['index']['searchIndex']
{%- endautoescape %}
", "search_index.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/enhanced/search_index.twig");
    }
}
