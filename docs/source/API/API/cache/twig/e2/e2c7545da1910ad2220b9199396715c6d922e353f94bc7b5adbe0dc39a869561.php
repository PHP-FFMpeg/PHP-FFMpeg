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

/* namespaces.twig */
class __TwigTemplate_ca63743e4b929b928ce30d6aaa3623db02fb1cfb9d90ceb36e89e6113da2fa5e extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'body_class' => [$this, 'block_body_class'],
            'header' => [$this, 'block_header'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout/base.twig", "namespaces.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = [])
    {
        echo "Namespaces | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 5
    public function block_body_class($context, array $blocks = [])
    {
        echo "frame";
    }

    // line 7
    public function block_header($context, array $blocks = [])
    {
        // line 8
        echo "    <div class=\"header\">
        <h1>";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
        echo "</h1>

        <ul>
            <li><a href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "classes-frame.html"), "html", null, true);
        echo "\">Classes</a></li>
            <li><a href=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "namespaces-frame.html"), "html", null, true);
        echo "\">Namespaces</a></li>
        </ul>
    </div>
";
    }

    // line 18
    public function block_content($context, array $blocks = [])
    {
        // line 19
        echo "    <h1>Namespaces</h1>

    <ul>
        ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["namespaces"] ?? $this->getContext($context, "namespaces")));
        foreach ($context['_seq'] as $context["_key"] => $context["namespace"]) {
            // line 23
            echo "            <li><a href=\"";
            echo twig_escape_filter($this->env, $context["namespace"], "html", null, true);
            echo "/namespace-frame.html\" target=\"index\">";
            echo twig_escape_filter($this->env, $context["namespace"], "html", null, true);
            echo "</a></li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['namespace'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        echo "    </ul>
";
    }

    public function getTemplateName()
    {
        return "namespaces.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 25,  91 => 23,  87 => 22,  82 => 19,  79 => 18,  71 => 13,  67 => 12,  61 => 9,  58 => 8,  55 => 7,  49 => 5,  42 => 3,  32 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"layout/base.twig\" %}

{% block title %}Namespaces | {{ parent() }}{% endblock %}

{% block body_class 'frame' %}

{% block header %}
    <div class=\"header\">
        <h1>{{ project.config('title') }}</h1>

        <ul>
            <li><a href=\"{{ path('classes-frame.html') }}\">Classes</a></li>
            <li><a href=\"{{ path('namespaces-frame.html') }}\">Namespaces</a></li>
        </ul>
    </div>
{% endblock %}

{% block content %}
    <h1>Namespaces</h1>

    <ul>
        {% for namespace in namespaces %}
            <li><a href=\"{{ namespace }}/namespace-frame.html\" target=\"index\">{{ namespace }}</a></li>
        {% endfor %}
    </ul>
{% endblock %}
", "namespaces.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/namespaces.twig");
    }
}
