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

/* layout/layout.twig */
class __TwigTemplate_606231d6264921e865f7a7bcd9b86a5a481bb04478d18c38348f2aad482ad14f extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'html' => [$this, 'block_html'],
            'body_class' => [$this, 'block_body_class'],
            'header' => [$this, 'block_header'],
            'content' => [$this, 'block_content'],
            'footer' => [$this, 'block_footer'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout/base.twig", "layout/layout.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_html($context, array $blocks = [])
    {
        // line 4
        echo "    <body id=\"";
        $this->displayBlock('body_class', $context, $blocks);
        echo "\">
        ";
        // line 5
        $this->displayBlock('header', $context, $blocks);
        // line 6
        echo "        <div class=\"content\">
            ";
        // line 7
        $this->displayBlock('content', $context, $blocks);
        // line 8
        echo "        </div>
        ";
        // line 9
        $this->displayBlock('footer', $context, $blocks);
        // line 10
        echo "    </body>
";
    }

    // line 4
    public function block_body_class($context, array $blocks = [])
    {
        echo "";
    }

    // line 5
    public function block_header($context, array $blocks = [])
    {
        echo "";
    }

    // line 7
    public function block_content($context, array $blocks = [])
    {
        echo "";
    }

    // line 9
    public function block_footer($context, array $blocks = [])
    {
        echo "";
    }

    public function getTemplateName()
    {
        return "layout/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 9,  80 => 7,  74 => 5,  68 => 4,  63 => 10,  61 => 9,  58 => 8,  56 => 7,  53 => 6,  51 => 5,  46 => 4,  43 => 3,  33 => 1,);
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

{% block html %}
    <body id=\"{% block body_class '' %}\">
        {% block header '' %}
        <div class=\"content\">
            {% block content '' %}
        </div>
        {% block footer '' %}
    </body>
{% endblock %}
", "layout/layout.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/default/layout/layout.twig");
    }
}
