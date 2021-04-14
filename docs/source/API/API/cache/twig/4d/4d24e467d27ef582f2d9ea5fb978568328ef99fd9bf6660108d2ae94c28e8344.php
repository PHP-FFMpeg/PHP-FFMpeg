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

/* pages/index.twig */
class __TwigTemplate_d7ebe96949ad8beb7eedc1907864c6a1a122d974bcd2dd3ab5f438e89dd52f1c extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body_class' => [$this, 'block_body_class'],
            'content_header' => [$this, 'block_content_header'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return $this->loadTemplate(($context["page_layout"] ?? $this->getContext($context, "page_layout")), "pages/index.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"] = $this->loadTemplate("macros.twig", "pages/index.twig", 3)->unwrap();
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo "Index | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 7
    public function block_body_class($context, array $blocks = [])
    {
        echo "overview";
    }

    // line 9
    public function block_content_header($context, array $blocks = [])
    {
        // line 10
        echo "    <div class=\"type\">Index</div>

    ";
        // line 12
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(range("A", "Z"));
        foreach ($context['_seq'] as $context["_key"] => $context["letter"]) {
            // line 13
            echo "        ";
            if (($this->getAttribute(($context["items"] ?? null), $context["letter"], [], "array", true, true) && (twig_length_filter($this->env, $this->getAttribute(($context["items"] ?? $this->getContext($context, "items")), $context["letter"], [], "array")) > 1))) {
                // line 14
                echo "            <a href=\"#letter";
                echo twig_escape_filter($this->env, $context["letter"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["letter"], "html", null, true);
                echo "</a>
        ";
            } else {
                // line 16
                echo "            ";
                echo twig_escape_filter($this->env, $context["letter"], "html", null, true);
                echo "
        ";
            }
            // line 18
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['letter'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    // line 21
    public function block_content($context, array $blocks = [])
    {
        // line 22
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["items"] ?? $this->getContext($context, "items")));
        foreach ($context['_seq'] as $context["letter"] => $context["elements"]) {
            // line 23
            echo "<h2 id=\"letter";
            echo twig_escape_filter($this->env, $context["letter"], "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $context["letter"], "html", null, true);
            echo "</h2>
        <dl id=\"index\">";
            // line 25
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["elements"]);
            foreach ($context['_seq'] as $context["_key"] => $context["element"]) {
                // line 26
                $context["type"] = $this->getAttribute($context["element"], 0, [], "array");
                // line 27
                $context["value"] = $this->getAttribute($context["element"], 1, [], "array");
                // line 28
                if (("class" == ($context["type"] ?? $this->getContext($context, "type")))) {
                    // line 29
                    echo "<dt>";
                    echo $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"]->getclass_link(($context["value"] ?? $this->getContext($context, "value")));
                    if (($context["has_namespaces"] ?? $this->getContext($context, "has_namespaces"))) {
                        echo " &mdash; <em>Class in namespace ";
                        echo $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"]->getnamespace_link($this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "namespace", []));
                    }
                    echo "</em></dt>
                    <dd>";
                    // line 30
                    echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "shortdesc", []), ($context["value"] ?? $this->getContext($context, "value")));
                    echo "</dd>";
                } elseif (("method" ==                 // line 31
($context["type"] ?? $this->getContext($context, "type")))) {
                    // line 32
                    echo "<dt>";
                    echo $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"]->getmethod_link(($context["value"] ?? $this->getContext($context, "value")));
                    echo "() &mdash; <em>Method in class ";
                    echo $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"]->getclass_link($this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "class", []));
                    echo "</em></dt>
                    <dd>";
                    // line 33
                    echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "shortdesc", []), $this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "class", []));
                    echo "</dd>";
                } elseif (("property" ==                 // line 34
($context["type"] ?? $this->getContext($context, "type")))) {
                    // line 35
                    echo "<dt>\$";
                    echo $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"]->getproperty_link(($context["value"] ?? $this->getContext($context, "value")));
                    echo " &mdash; <em>Property in class ";
                    echo $context["__internal_b67eacdc879dc1dcbdb86d3df75d3cbc02aea421a8f1d6acd9ec492e70bd217a"]->getclass_link($this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "class", []));
                    echo "</em></dt>
                    <dd>";
                    // line 36
                    echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "shortdesc", []), $this->getAttribute(($context["value"] ?? $this->getContext($context, "value")), "class", []));
                    echo "</dd>";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['element'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            echo "        </dl>";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['letter'], $context['elements'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
    }

    public function getTemplateName()
    {
        return "pages/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  159 => 39,  151 => 36,  144 => 35,  142 => 34,  139 => 33,  132 => 32,  130 => 31,  127 => 30,  118 => 29,  116 => 28,  114 => 27,  112 => 26,  108 => 25,  101 => 23,  96 => 22,  93 => 21,  85 => 18,  79 => 16,  71 => 14,  68 => 13,  64 => 12,  60 => 10,  57 => 9,  51 => 7,  44 => 5,  40 => 1,  38 => 3,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% extends page_layout %}

{% from \"macros.twig\" import class_link, namespace_link, method_link, property_link %}

{% block title %}Index | {{ parent() }}{% endblock %}

{% block body_class 'overview' %}

{% block content_header %}
    <div class=\"type\">Index</div>

    {% for letter in 'A'..'Z' %}
        {% if items[letter] is defined and items[letter]|length > 1 %}
            <a href=\"#letter{{ letter }}\">{{ letter }}</a>
        {% else %}
            {{ letter }}
        {% endif %}
    {% endfor %}
{% endblock %}

{% block content %}
    {% for letter, elements in items -%}
        <h2 id=\"letter{{ letter }}\">{{ letter }}</h2>
        <dl id=\"index\">
            {%- for element in elements %}
                {%- set type = element[0] %}
                {%- set value = element[1] %}
                {%- if 'class' == type -%}
                    <dt>{{ class_link(value) }}{% if has_namespaces %} &mdash; <em>Class in namespace {{ namespace_link(value.namespace) }}{% endif %}</em></dt>
                    <dd>{{ value.shortdesc|desc(value) }}</dd>
                {%- elseif 'method' == type -%}
                    <dt>{{ method_link(value) }}() &mdash; <em>Method in class {{ class_link(value.class) }}</em></dt>
                    <dd>{{ value.shortdesc|desc(value.class) }}</dd>
                {%- elseif 'property' == type -%}
                    <dt>\${{ property_link(value) }} &mdash; <em>Property in class {{ class_link(value.class) }}</em></dt>
                    <dd>{{ value.shortdesc|desc(value.class) }}</dd>
                {%- endif %}
            {%- endfor %}
        </dl>
    {%- endfor %}
{% endblock %}
", "pages/index.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/index.twig");
    }
}
