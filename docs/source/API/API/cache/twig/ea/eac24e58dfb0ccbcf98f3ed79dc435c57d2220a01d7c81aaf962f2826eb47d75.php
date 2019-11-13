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

/* pages/namespace.twig */
class __TwigTemplate_4b5dbc3bc5c22fa9ee58882b59e281fefbed5d0d757f3ded80bd2f60db9de993 extends \Twig\Template
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
        return $this->loadTemplate(($context["page_layout"] ?? $this->getContext($context, "page_layout")), "pages/namespace.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["__internal_f3c7b8014d2f21365f4c3b0f9d84a1743b7c316bd41df433d2e47925adc76a49"] = $this->loadTemplate("macros.twig", "pages/namespace.twig", 3)->unwrap();
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo twig_escape_filter($this->env, ($context["namespace"] ?? $this->getContext($context, "namespace")), "html", null, true);
        echo " | ";
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
        echo "    <div class=\"type\">Namespace</div>
    <h1>";
        // line 11
        echo twig_escape_filter($this->env, ($context["namespace"] ?? $this->getContext($context, "namespace")), "html", null, true);
        echo "</h1>
";
    }

    // line 14
    public function block_content($context, array $blocks = [])
    {
        // line 15
        echo "    ";
        if (($context["classes"] ?? $this->getContext($context, "classes"))) {
            // line 16
            echo "        <table>
            ";
            // line 17
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["classes"] ?? $this->getContext($context, "classes")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 18
                echo "                <tr>
                    <td>";
                // line 19
                echo $context["__internal_f3c7b8014d2f21365f4c3b0f9d84a1743b7c316bd41df433d2e47925adc76a49"]->getclass_link($context["class"]);
                echo "</td>
                    <td class=\"last\">";
                // line 20
                echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["class"], "shortdesc", []), $context["class"]);
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 23
            echo "        </table>
    ";
        }
        // line 25
        echo "
    ";
        // line 26
        if (($context["interfaces"] ?? $this->getContext($context, "interfaces"))) {
            // line 27
            echo "        <h2>Interfaces</h2>
        <table>
            ";
            // line 29
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["interfaces"] ?? $this->getContext($context, "interfaces")));
            foreach ($context['_seq'] as $context["_key"] => $context["interface"]) {
                // line 30
                echo "                <tr>
                    <td>";
                // line 31
                echo $context["__internal_f3c7b8014d2f21365f4c3b0f9d84a1743b7c316bd41df433d2e47925adc76a49"]->getclass_link($context["interface"]);
                echo "</td>
                    <td class=\"last\">";
                // line 32
                echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["interface"], "shortdesc", []), $context["interface"]);
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['interface'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 35
            echo "        </table>
    ";
        }
        // line 37
        echo "
    ";
        // line 38
        if (($context["exceptions"] ?? $this->getContext($context, "exceptions"))) {
            // line 39
            echo "        <h2>Exceptions</h2>
        <table>
            ";
            // line 41
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["exceptions"] ?? $this->getContext($context, "exceptions")));
            foreach ($context['_seq'] as $context["_key"] => $context["exception"]) {
                // line 42
                echo "                <tr>
                    <td>";
                // line 43
                echo $context["__internal_f3c7b8014d2f21365f4c3b0f9d84a1743b7c316bd41df433d2e47925adc76a49"]->getclass_link($context["exception"]);
                echo "</td>
                    <td class=\"last\">";
                // line 44
                echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["exception"], "shortdesc", []), $context["exception"]);
                echo "</td>
                </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['exception'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 47
            echo "        </table>
    ";
        }
    }

    public function getTemplateName()
    {
        return "pages/namespace.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  165 => 47,  156 => 44,  152 => 43,  149 => 42,  145 => 41,  141 => 39,  139 => 38,  136 => 37,  132 => 35,  123 => 32,  119 => 31,  116 => 30,  112 => 29,  108 => 27,  106 => 26,  103 => 25,  99 => 23,  90 => 20,  86 => 19,  83 => 18,  79 => 17,  76 => 16,  73 => 15,  70 => 14,  64 => 11,  61 => 10,  58 => 9,  52 => 7,  44 => 5,  40 => 1,  38 => 3,  32 => 1,);
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

{% from \"macros.twig\" import class_link %}

{% block title %}{{ namespace }} | {{ parent() }}{% endblock %}

{% block body_class 'overview' %}

{% block content_header %}
    <div class=\"type\">Namespace</div>
    <h1>{{ namespace }}</h1>
{% endblock %}

{% block content %}
    {% if classes %}
        <table>
            {% for class in classes %}
                <tr>
                    <td>{{ class_link(class) }}</td>
                    <td class=\"last\">{{ class.shortdesc|desc(class) }}</td>
                </tr>
            {% endfor %}
        </table>
    {% endif %}

    {% if interfaces %}
        <h2>Interfaces</h2>
        <table>
            {% for interface in interfaces %}
                <tr>
                    <td>{{ class_link(interface) }}</td>
                    <td class=\"last\">{{ interface.shortdesc|desc(interface) }}</td>
                </tr>
            {% endfor %}
        </table>
    {% endif %}

    {% if exceptions %}
        <h2>Exceptions</h2>
        <table>
            {% for exception in exceptions %}
                <tr>
                    <td>{{ class_link(exception) }}</td>
                    <td class=\"last\">{{ exception.shortdesc|desc(exception) }}</td>
                </tr>
            {% endfor %}
        </table>
    {% endif %}
{% endblock %}
", "pages/namespace.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/namespace.twig");
    }
}
