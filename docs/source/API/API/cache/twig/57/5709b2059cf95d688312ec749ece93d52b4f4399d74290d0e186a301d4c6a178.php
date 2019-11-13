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

/* pages/classes.twig */
class __TwigTemplate_e54cf286fc59c580188cca2520e005e73364e4cca818dc8d37839f2d5af43406 extends \Twig\Template
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
        return $this->loadTemplate(($context["page_layout"] ?? $this->getContext($context, "page_layout")), "pages/classes.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 3
        $context["__internal_c7bd67512a0743a48b1c44d3b67c8677807167167f6da02ac43310d81032cb6f"] = $this->loadTemplate("macros.twig", "pages/classes.twig", 3)->unwrap();
        // line 1
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo "Classes | ";
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
        echo "    <h1>Classes</h1>
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
            echo "            <tr>
                <td>
                    ";
            // line 18
            if ($this->getAttribute($context["class"], "interface", [])) {
                echo "<em>";
            }
            // line 19
            echo "                    ";
            echo $context["__internal_c7bd67512a0743a48b1c44d3b67c8677807167167f6da02ac43310d81032cb6f"]->getclass_link($context["class"], ["target" => "main"], true);
            echo "
                    ";
            // line 20
            if ($this->getAttribute($context["class"], "interface", [])) {
                echo "</em>";
            }
            // line 21
            echo "                </td>
                <td>
                    ";
            // line 23
            echo $this->env->getExtension('Sami\Renderer\TwigExtension')->parseDesc($context, $this->getAttribute($context["class"], "shortdesc", []), $context["class"]);
            echo "
                </td>
            </tr>
        ";
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
        return "pages/classes.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  106 => 27,  96 => 23,  92 => 21,  88 => 20,  83 => 19,  79 => 18,  75 => 16,  71 => 15,  68 => 14,  65 => 13,  60 => 10,  57 => 9,  51 => 7,  44 => 5,  40 => 1,  38 => 3,  32 => 1,);
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

{% block title %}Classes | {{ parent() }}{% endblock %}

{% block body_class 'overview' %}

{% block content_header %}
    <h1>Classes</h1>
{% endblock %}

{% block content %}
    <table>
        {% for class in classes %}
            <tr>
                <td>
                    {% if class.interface %}<em>{% endif %}
                    {{ class_link(class, {'target': 'main'}, true) }}
                    {% if class.interface %}</em>{% endif %}
                </td>
                <td>
                    {{ class.shortdesc|desc(class) }}
                </td>
            </tr>
        {% endfor %}
    </table>
{% endblock %}
", "pages/classes.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/classes.twig");
    }
}
