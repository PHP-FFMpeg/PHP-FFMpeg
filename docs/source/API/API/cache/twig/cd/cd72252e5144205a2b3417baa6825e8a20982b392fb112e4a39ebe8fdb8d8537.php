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

/* pages/traits.twig */
class __TwigTemplate_2a2481f89e253d808d02fe3b5d53848b86e4af0b4ba336cd8bd5f485ef291f32 extends \Twig\Template
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
        return $this->loadTemplate(($context["page_layout"] ?? $this->getContext($context, "page_layout")), "pages/traits.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["__internal_bdb6e81c93620f7a956cf4c0b89fc8712dfc616598ccab0e10371c936a153a37"] = $this->loadTemplate("macros.twig", "pages/traits.twig", 3)->unwrap();
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo "Traits | ";
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
        echo "    <h1>Traits</h1>
";
    }

    // line 13
    public function block_content($context, array $blocks = [])
    {
        // line 14
        echo "    <table>
        ";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["classes"] ?? $this->getContext($context, "classes")));
        foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
            // line 16
            echo "            ";
            if ($this->getAttribute($context["class"], "trait", [])) {
                // line 17
                echo "                <tr>
                    <td>
                        ";
                // line 19
                echo $context["__internal_bdb6e81c93620f7a956cf4c0b89fc8712dfc616598ccab0e10371c936a153a37"]->getclass_link($context["class"], ["target" => "main"], true);
                echo "
                    </td>
                    <td>
                        ";
                // line 22
                echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["class"], "shortdesc", []), $context["class"]);
                echo "
                    </td>
                </tr>
            ";
            }
            // line 26
            echo "        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 27
        echo "    </table>
";
    }

    public function getTemplateName()
    {
        return "pages/traits.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  101 => 27,  95 => 26,  88 => 22,  82 => 19,  78 => 17,  75 => 16,  71 => 15,  68 => 14,  65 => 13,  60 => 10,  57 => 9,  51 => 7,  44 => 5,  40 => 1,  38 => 3,  32 => 1,);
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

{% block title %}Traits | {{ parent() }}{% endblock %}

{% block body_class 'overview' %}

{% block content_header %}
    <h1>Traits</h1>
{% endblock %}

{% block content %}
    <table>
        {% for class in classes %}
            {% if class.trait %}
                <tr>
                    <td>
                        {{ class_link(class, {'target': 'main'}, true) }}
                    </td>
                    <td>
                        {{ class.shortdesc|desc(class) }}
                    </td>
                </tr>
            {% endif %}
        {% endfor %}
    </table>
{% endblock %}
", "pages/traits.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/traits.twig");
    }
}
