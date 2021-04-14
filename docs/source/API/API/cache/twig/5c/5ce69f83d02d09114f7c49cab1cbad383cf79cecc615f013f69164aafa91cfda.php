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

/* namespace.twig */
class __TwigTemplate_55c86e200b578a7eb9618c02274c06cd3ccf80cdb480968bcd439f7a6396d15a extends \Twig\Template
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
        $context["__internal_77823f7727043a329e0a67872707061fcc3ccc26c8907b2dc75bf7ec6229dd6f"] = $this->loadTemplate("macros.twig", "namespace.twig", 3)->unwrap();
        // line 1
        $this->parent = $this->loadTemplate("layout/base.twig", "namespace.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
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
            <li><a href=\"";
        // line 15
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "namespaces-frame.html"), "html", null, true);
        echo "\">Namespaces</a></li>
        </ul>
    </div>
";
    }

    // line 20
    public function block_content($context, array $blocks = [])
    {
        // line 21
        echo "    <h1>";
        echo $context["__internal_77823f7727043a329e0a67872707061fcc3ccc26c8907b2dc75bf7ec6229dd6f"]->getnamespace_link(($context["namespace"] ?? $this->getContext($context, "namespace")), ["target" => "main"]);
        echo "</h1>

    ";
        // line 23
        if (($context["classes"] ?? $this->getContext($context, "classes"))) {
            // line 24
            echo "        <ul>
            ";
            // line 25
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["classes"] ?? $this->getContext($context, "classes")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 26
                echo "                <li>";
                echo $context["__internal_77823f7727043a329e0a67872707061fcc3ccc26c8907b2dc75bf7ec6229dd6f"]->getclass_link($context["class"], ["target" => "main"]);
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 28
            echo "        </ul>
    ";
        }
        // line 30
        echo "
    ";
        // line 31
        if (($context["interfaces"] ?? $this->getContext($context, "interfaces"))) {
            // line 32
            echo "        <h2>Interfaces</h2>
        <ul>
            ";
            // line 34
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["interfaces"] ?? $this->getContext($context, "interfaces")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 35
                echo "                <li>";
                echo $context["__internal_77823f7727043a329e0a67872707061fcc3ccc26c8907b2dc75bf7ec6229dd6f"]->getclass_link($context["class"], ["target" => "main"]);
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 37
            echo "        </ul>
    ";
        }
        // line 39
        echo "
    ";
        // line 40
        if (($context["exceptions"] ?? $this->getContext($context, "exceptions"))) {
            // line 41
            echo "        <h2>Exceptions</h2>
        <ul>
            ";
            // line 43
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["exceptions"] ?? $this->getContext($context, "exceptions")));
            foreach ($context['_seq'] as $context["_key"] => $context["class"]) {
                // line 44
                echo "                <li>";
                echo $context["__internal_77823f7727043a329e0a67872707061fcc3ccc26c8907b2dc75bf7ec6229dd6f"]->getclass_link($context["class"], ["target" => "main"]);
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['class'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 46
            echo "        </ul>
    ";
        }
    }

    public function getTemplateName()
    {
        return "namespace.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  162 => 46,  153 => 44,  149 => 43,  145 => 41,  143 => 40,  140 => 39,  136 => 37,  127 => 35,  123 => 34,  119 => 32,  117 => 31,  114 => 30,  110 => 28,  101 => 26,  97 => 25,  94 => 24,  92 => 23,  86 => 21,  83 => 20,  75 => 15,  71 => 14,  65 => 11,  62 => 10,  59 => 9,  53 => 7,  45 => 5,  40 => 1,  38 => 3,  32 => 1,);
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

{% from \"macros.twig\" import class_link, namespace_link %}

{% block title %}{{ namespace }} | {{ parent() }}{% endblock %}

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
    <h1>{{ namespace_link(namespace, {'target': 'main'}) }}</h1>

    {% if classes %}
        <ul>
            {% for class in classes %}
                <li>{{ class_link(class, {'target': 'main'}) }}</li>
            {% endfor %}
        </ul>
    {% endif %}

    {% if interfaces %}
        <h2>Interfaces</h2>
        <ul>
            {% for class in interfaces %}
                <li>{{ class_link(class, {'target': 'main'}) }}</li>
            {% endfor %}
        </ul>
    {% endif %}

    {% if exceptions %}
        <h2>Exceptions</h2>
        <ul>
            {% for class in exceptions %}
                <li>{{ class_link(class, {'target': 'main'}) }}</li>
            {% endfor %}
        </ul>
    {% endif %}
{% endblock %}
", "namespace.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/namespace.twig");
    }
}
