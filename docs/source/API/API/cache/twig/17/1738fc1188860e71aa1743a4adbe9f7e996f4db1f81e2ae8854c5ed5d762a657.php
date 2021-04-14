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

/* pages/opensearch.twig */
class __TwigTemplate_702806b39e1a88bee3eb9ad58ca14e696eafa9197a23e641d12a291a1a2258ea extends \Twig\Template
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
        if ($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "base_url"], "method")) {
            // line 2
            echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
    <OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\" xmlns:referrer=\"http://a9.com/-/opensearch/extensions/referrer/\">
        <ShortName>";
            // line 4
            echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "version", []), "html", null, true);
            echo ")</ShortName>
        <Description>Searches ";
            // line 5
            echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
            echo " (";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "version", []), "html", null, true);
            echo ")</Description>
        <Tags>";
            // line 6
            echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
            echo "</Tags>
        ";
            // line 7
            if ($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "favicon"], "method")) {
                // line 8
                echo "<Image height=\"16\" width=\"16\" type=\"image/x-icon\">";
                echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "favicon"], "method"), "html", null, true);
                echo "</Image>
        ";
            }
            // line 10
            echo "        <Url type=\"text/html\" method=\"GET\" template=\"";
            echo twig_escape_filter($this->env, (twig_replace_filter($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "base_url"], "method"), ["%version%" => $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "version", [])]) . "/index.html?q={searchTerms}&src={referrer:source?}"), "html", null, true);
            echo "\"/>
        <InputEncoding>UTF-8</InputEncoding>
        <AdultContent>false</AdultContent>
    </OpenSearchDescription>
";
        }
    }

    public function getTemplateName()
    {
        return "pages/opensearch.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 10,  54 => 8,  52 => 7,  48 => 6,  42 => 5,  36 => 4,  32 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% if project.config('base_url') -%}
    <?xml version=\"1.0\" encoding=\"UTF-8\"?>
    <OpenSearchDescription xmlns=\"http://a9.com/-/spec/opensearch/1.1/\" xmlns:referrer=\"http://a9.com/-/opensearch/extensions/referrer/\">
        <ShortName>{{ project.config('title') }} ({{ project.version }})</ShortName>
        <Description>Searches {{ project.config('title') }} ({{ project.version }})</Description>
        <Tags>{{ project.config('title') }}</Tags>
        {% if project.config('favicon') -%}
            <Image height=\"16\" width=\"16\" type=\"image/x-icon\">{{ project.config('favicon') }}</Image>
        {% endif %}
        <Url type=\"text/html\" method=\"GET\" template=\"{{ project.config('base_url')|replace({'%version%': project.version}) ~ '/index.html?q={searchTerms}&src={referrer:source?}' }}\"/>
        <InputEncoding>UTF-8</InputEncoding>
        <AdultContent>false</AdultContent>
    </OpenSearchDescription>
{% endif %}
", "pages/opensearch.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/pages/opensearch.twig");
    }
}
