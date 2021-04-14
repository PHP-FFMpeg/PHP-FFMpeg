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

/* tree.twig */
class __TwigTemplate_b74e792e7f105c929aee624e29df8bd9a9989ec467d5cde3204a5fc4012f3717 extends \Twig\Template
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
        echo "var tree = ";
        echo $this->getAttribute($this, "element", [0 => ($context["tree"] ?? $this->getContext($context, "tree"))], "method");
        echo "

";
    }

    // line 3
    public function getelement($__tree__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals([
            "tree" => $__tree__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start();
        try {
            // line 5
            echo "[";
            // line 6
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["tree"] ?? $this->getContext($context, "tree")));
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
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 7
                echo "[";
                // line 8
                echo twig_jsonencode_filter($this->getAttribute($context["element"], 0, [], "array"));
                echo ",";
                // line 9
                echo (($this->getAttribute($context["element"], 1, [], "array")) ? (twig_jsonencode_filter((( !$this->getAttribute($context["element"], 2, [], "array")) ? ($this->env->getExtension('Sami\Renderer\TwigExtension')->pathForClass($context, $this->getAttribute($context["element"], 1, [], "array"))) : ($this->env->getExtension('Sami\Renderer\TwigExtension')->pathForNamespace($context, $this->getAttribute($context["element"], 1, [], "array")))))) : (""));
                echo ",";
                // line 10
                echo twig_jsonencode_filter(((( !$this->getAttribute($context["element"], 2, [], "array") && $this->getAttribute($this->getAttribute($context["element"], 1, [], "array"), "parent", []))) ? ((" < " . $this->getAttribute($this->getAttribute($this->getAttribute($context["element"], 1, [], "array"), "parent", []), "shortname", []))) : ("")));
                echo ",
                ";
                // line 11
                echo $this->getAttribute($this, "element", [0 => $this->getAttribute($context["element"], 2, [], "array")], "method");
                // line 12
                echo "]";
                // line 13
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
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 15
            echo "        ]";
        } catch (\Exception $e) {
            ob_end_clean();

            throw $e;
        } catch (\Throwable $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "tree.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  99 => 15,  85 => 13,  83 => 12,  81 => 11,  77 => 10,  74 => 9,  71 => 8,  69 => 7,  52 => 6,  50 => 5,  38 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("var tree = {{ _self.element(tree) }}

{% macro element(tree) %}
    {%- autoescape false -%}
        [
            {%- for element in tree -%}
                [
                {{- element[0]|json_encode }},
                {{- element[1] ? ((not element[2] ? class_path(element[1]) : namespace_path(element[1]))|json_encode) : '' }},
                {{- (not element[2] and element[1].parent ? ' < ' ~ element[1].parent.shortname : '')|json_encode }},
                {{ _self.element(element[2]) -}}
                ]
                {{- loop.last ? '' : ',' }}
            {%- endfor %}
        ]
    {%- endautoescape %}
{% endmacro %}
", "tree.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/enhanced/tree.twig");
    }
}
