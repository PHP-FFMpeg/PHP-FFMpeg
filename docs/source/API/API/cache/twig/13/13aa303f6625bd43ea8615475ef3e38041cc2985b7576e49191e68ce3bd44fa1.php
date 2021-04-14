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

/* layout/page.twig */
class __TwigTemplate_33ee6d21fafd481a0cf3461c8a168e7076d66e135bca99f158357648e6c67e72 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->blocks = [
            'frame' => [$this, 'block_frame'],
        ];
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return "default/layout/page.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent = $this->loadTemplate("default/layout/page.twig", "layout/page.twig", 1);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_frame($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "layout/page.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 3,  29 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("{% extends \"default/layout/page.twig\" %}

{% block frame %}
{% endblock %}
", "layout/page.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/enhanced/layout/page.twig");
    }
}
