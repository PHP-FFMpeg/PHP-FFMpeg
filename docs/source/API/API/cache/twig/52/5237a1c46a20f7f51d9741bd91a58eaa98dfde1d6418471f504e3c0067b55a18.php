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

/* layout/frame.twig */
class __TwigTemplate_a40307f648ad2c3135dd89434384f820545ca39e350a32178286a6e7108f9058 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'head' => [$this, 'block_head'],
            'html' => [$this, 'block_html'],
            'frame_src' => [$this, 'block_frame_src'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "layout/base.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("layout/base.twig", "layout/frame.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = [])
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("head", $context, $blocks);
        echo "
    <script src=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "js/jquery-1.3.2.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "js/permalink.js"), "html", null, true);
        echo "\" type=\"text/javascript\" charset=\"utf-8\"></script>
";
    }

    // line 9
    public function block_html($context, array $blocks = [])
    {
        // line 10
        echo "    <frameset cols=\"20%,80%\" frameborder=\"1\" border=\"1\" bordercolor=\"#bbb\" framespacing=\"1\">
        <frame src=\"";
        // line 11
        $this->displayBlock('frame_src', $context, $blocks);
        echo "\" name=\"index\">
        <frame src=\"";
        // line 12
        echo ((($context["has_namespaces"] ?? $this->getContext($context, "has_namespaces"))) ? ("namespaces") : ("classes"));
        echo ".html\" name=\"main\" id=\"main-frame\">
        <noframes>
            <body>
                Your browser does not support frames. Go to the <a href=\"namespaces.html\">non-frame version</a>.
            </body>
        </noframes>
    </frameset>
";
    }

    // line 11
    public function block_frame_src($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "layout/frame.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 11,  69 => 12,  65 => 11,  62 => 10,  59 => 9,  53 => 6,  49 => 5,  44 => 4,  41 => 3,  31 => 1,);
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

{% block head %}
    {{ parent() }}
    <script src=\"{{ path('js/jquery-1.3.2.min.js') }}\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"{{ path('js/permalink.js') }}\" type=\"text/javascript\" charset=\"utf-8\"></script>
{% endblock %}

{% block html %}
    <frameset cols=\"20%,80%\" frameborder=\"1\" border=\"1\" bordercolor=\"#bbb\" framespacing=\"1\">
        <frame src=\"{% block frame_src %}{% endblock %}\" name=\"index\">
        <frame src=\"{{ has_namespaces ? 'namespaces' : 'classes' }}.html\" name=\"main\" id=\"main-frame\">
        <noframes>
            <body>
                Your browser does not support frames. Go to the <a href=\"namespaces.html\">non-frame version</a>.
            </body>
        </noframes>
    </frameset>
{% endblock %}
", "layout/frame.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/enhanced/layout/frame.twig");
    }
}
