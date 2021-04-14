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

/* classes.twig */
class __TwigTemplate_5caced55bb0d9195b0ed7fa4d792f1f5bf995d60a2e0f357ebb2cb7eec150416 extends \Twig\Template
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
        // line 3
        $context["__internal_55d0774540c47eec5b5be2f99bf5ac226cf6c71cb7241785aa84f7ace52ad885"] = $this->loadTemplate("macros.twig", "classes.twig", 3)->unwrap();
        // line 1
        $this->parent = $this->loadTemplate("layout/base.twig", "classes.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo "All Classes | ";
        $this->displayParentBlock("title", $context, $blocks);
    }

    // line 7
    public function block_body_class($context, array $blocks = [])
    {
        echo "frame";
    }

    // line 9
    public function block_header($context, array $blocks = [])
    {
        // line 10
        echo "    <div class=\"header\">
        <h1>";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
        echo "</h1>

        <ul>
            <li><a href=\"";
        // line 14
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "classes-frame.html"), "html", null, true);
        echo "\">Classes</a></li>
            ";
        // line 15
        if (($context["has_namespaces"] ?? $this->getContext($context, "has_namespaces"))) {
            // line 16
            echo "                <li><a href=\"";
            echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "namespaces-frame.html"), "html", null, true);
            echo "\">Namespaces</a></li>
            ";
        }
        // line 18
        echo "        </ul>
    </div>
";
    }

    // line 22
    public function block_content($context, array $blocks = [])
    {
        // line 23
        echo "    <h1>Classes</h1>
    <ul>
        ";
        // line 25
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["classes"] ?? $this->getContext($context, "classes")));
        foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
            // line 26
            echo "            <li>
                ";
            // line 27
            if ($this->getAttribute($context["class"], "isinterface", [])) {
                echo "<em>";
            }
            // line 28
            echo "                ";
            echo $context["__internal_55d0774540c47eec5b5be2f99bf5ac226cf6c71cb7241785aa84f7ace52ad885"]->getclass_link($context["class"], ["target" => "main"]);
            echo "
                ";
            // line 29
            if ($this->getAttribute($context["class"], "isinterface", [])) {
                echo "</em>";
            }
            // line 30
            echo "            </li>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "    </ul>
";
    }

    public function getTemplateName()
    {
        return "classes.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  122 => 32,  115 => 30,  111 => 29,  106 => 28,  102 => 27,  99 => 26,  95 => 25,  91 => 23,  88 => 22,  82 => 18,  76 => 16,  74 => 15,  70 => 14,  64 => 11,  61 => 10,  58 => 9,  52 => 7,  45 => 5,  40 => 1,  38 => 3,  32 => 1,);
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

{% from \"macros.twig\" import class_link %}

{% block title %}All Classes | {{ parent() }}{% endblock %}

{% block body_class 'frame' %}

{% block header %}
    <div class=\"header\">
        <h1>{{ project.config('title') }}</h1>

        <ul>
            <li><a href=\"{{ path('classes-frame.html') }}\">Classes</a></li>
            {% if has_namespaces %}
                <li><a href=\"{{ path('namespaces-frame.html') }}\">Namespaces</a></li>
            {% endif %}
        </ul>
    </div>
{% endblock %}

{% block content %}
    <h1>Classes</h1>
    <ul>
        {% for class in classes %}
            <li>
                {% if class.isinterface %}<em>{% endif %}
                {{ class_link(class, {'target': 'main'}) }}
                {% if class.isinterface %}</em>{% endif %}
            </li>
        {% endfor %}
    </ul>
{% endblock %}
", "classes.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/classes.twig");
    }
}
