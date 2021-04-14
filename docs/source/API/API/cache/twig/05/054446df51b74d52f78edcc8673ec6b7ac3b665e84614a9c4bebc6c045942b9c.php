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

/* layout/base.twig */
class __TwigTemplate_60efc6a0511b458df3881a25fd53d3d3b9f3b06ca2d47cdb026ec073cd150ca8 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'head' => [$this, 'block_head'],
            'html' => [$this, 'block_html'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\" />
        <meta name=\"robots\" content=\"index, follow, all\" />
        <title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 7
        $this->displayBlock('head', $context, $blocks);
        // line 10
        echo "        ";
        if ($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "favicon"], "method")) {
            // line 11
            echo "            <link rel=\"shortcut icon\" href=\"";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "favicon"], "method"), "html", null, true);
            echo "\" />
        ";
        }
        // line 13
        echo "        ";
        if ($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "base_url"], "method")) {
            // line 14
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "versions", []));
            foreach ($context['_seq'] as $context["_key"] => $context["version"]) {
                // line 15
                echo "<link rel=\"search\" type=\"application/opensearchdescription+xml\" href=\"";
                echo twig_escape_filter($this->env, twig_replace_filter($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "base_url"], "method"), ["%version%" => $context["version"]]), "html", null, true);
                echo "/opensearch.xml\" title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
                echo " (";
                echo twig_escape_filter($this->env, $context["version"], "html", null, true);
                echo ")\" />
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['version'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 18
        echo "    </head>
    ";
        // line 19
        $this->displayBlock('html', $context, $blocks);
        // line 21
        echo "</html>
";
    }

    // line 6
    public function block_title($context, array $blocks = [])
    {
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
    }

    // line 7
    public function block_head($context, array $blocks = [])
    {
        // line 8
        echo "            <link rel=\"stylesheet\" type=\"text/css\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "stylesheet.css"), "html", null, true);
        echo "\">
        ";
    }

    // line 19
    public function block_html($context, array $blocks = [])
    {
        // line 20
        echo "    ";
    }

    public function getTemplateName()
    {
        return "layout/base.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  105 => 20,  102 => 19,  95 => 8,  92 => 7,  86 => 6,  81 => 21,  79 => 19,  76 => 18,  62 => 15,  58 => 14,  55 => 13,  49 => 11,  46 => 10,  44 => 7,  40 => 6,  33 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\" />
        <meta name=\"robots\" content=\"index, follow, all\" />
        <title>{% block title project.config('title') %}</title>
        {% block head %}
            <link rel=\"stylesheet\" type=\"text/css\" href=\"{{ path('stylesheet.css') }}\">
        {% endblock %}
        {% if project.config('favicon') %}
            <link rel=\"shortcut icon\" href=\"{{ project.config('favicon') }}\" />
        {% endif %}
        {% if project.config('base_url') %}
            {%- for version in project.versions -%}
                <link rel=\"search\" type=\"application/opensearchdescription+xml\" href=\"{{ project.config('base_url')|replace({'%version%': version}) }}/opensearch.xml\" title=\"{{ project.config('title') }} ({{ version }})\" />
            {% endfor -%}
        {% endif %}
    </head>
    {% block html %}
    {% endblock %}
</html>
", "layout/base.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/layout/base.twig");
    }
}
