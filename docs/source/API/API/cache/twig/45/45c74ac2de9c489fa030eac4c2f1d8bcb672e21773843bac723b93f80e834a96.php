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

/* pages/namespaces.twig */
class __TwigTemplate_b274b4be338be1cff32cbd6a87ed1b6e430f3858cacfabcaacfee3372e5ff9e7 extends \Twig\Template
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
        return $this->loadTemplate(($context["page_layout"] ?? $this->getContext($context, "page_layout")), "pages/namespaces.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["__internal_7b39f85c0c1fbfc19fc75e86dde9109aff7d62351b2973c8fa9767c5a54162cc"] = $this->loadTemplate("macros.twig", "pages/namespaces.twig", 3)->unwrap();
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo "Namespaces | ";
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
        echo "    <h1>Namespaces</h1>
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
        $context['_seq'] = twig_ensure_traversable(($context["namespaces"] ?? $this->getContext($context, "namespaces")));
        foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
            // line 16
            echo "            <tr>
                <td>";
            // line 17
            echo $context["__internal_7b39f85c0c1fbfc19fc75e86dde9109aff7d62351b2973c8fa9767c5a54162cc"]->getnamespace_link($context["namespace"]);
            echo "</td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 20
        echo "    </table>
";
    }

    public function getTemplateName()
    {
        return "pages/namespaces.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  87 => 20,  78 => 17,  75 => 16,  71 => 15,  68 => 14,  65 => 13,  60 => 10,  57 => 9,  51 => 7,  44 => 5,  40 => 1,  38 => 3,  32 => 1,);
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

{% from \"macros.twig\" import namespace_link %}

{% block title %}Namespaces | {{ parent() }}{% endblock %}

{% block body_class 'overview' %}

{% block content_header %}
    <h1>Namespaces</h1>
{% endblock %}

{% block content %}
    <table>
        {% for namespace in namespaces %}
            <tr>
                <td>{{ namespace_link(namespace) }}</td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}
", "pages/namespaces.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/namespaces.twig");
    }
}
