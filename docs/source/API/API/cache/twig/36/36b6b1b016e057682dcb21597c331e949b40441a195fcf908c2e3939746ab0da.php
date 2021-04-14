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

/* panel.twig */
class __TwigTemplate_61f99f8c82e48d24b41877b9031dc496a329183604be90c031a939c195fdc87e extends \Twig\Template
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
        echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">
<html lang=\"en\">
<head>
    <title>";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
        echo "</title>
    <link rel=\"stylesheet\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "css/reset.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"screen\" charset=\"utf-8\">
    <link rel=\"stylesheet\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "css/panel.css"), "html", null, true);
        echo "\" type=\"text/css\" media=\"screen\" charset=\"utf-8\">
    <script src=\"tree.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "js/jquery-1.3.2.min.js"), "html", null, true);
        echo "\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "js/searchdoc.js"), "html", null, true);
        echo "\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script type=\"text/javascript\" charset=\"utf-8\">
        //<![CDATA[
        \$(document).ready(function(){
            \$('#version-switcher').change(function() {
                window.parent.location = \$(this).val()
            })
        })
       \$(function() {
           \$.ajax({
             url: 'search_index.js',
             dataType: 'script',
             success: function () {
                 \$('.loader').css('display', 'none');
                 var panel = new Searchdoc.Panel(\$('#panel'), search_data, tree, parent.frames[1]);
                 \$('#search').focus();

                 for (var i=0; i < ";
        // line 26
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "default_opened_level", 1 => 0], "method"), "html", null, true);
        echo "; i++) {
                     \$('.level_' + i).each(function (\$li) {
                         panel.tree.toggle(\$(this));
                     });
                 }

                 var s = window.parent.location.search.match(/\\?q=([^&]+)/);
                 if (s) {
                     s = decodeURIComponent(s[1]).replace(/\\+/g, ' ');
                     if (s.length > 0)
                     {
                         \$('#search').val(s);
                         panel.search(s, true);
                     }
                 }
             }
           });
       })
        //]]>
    </script>
</head>
<body>
    <div class=\"panel panel_tree\" id=\"panel\">
        <div class=\"loader\">
            <img src=\"";
        // line 50
        echo twig_escape_filter($this->env, $this->env->getExtension('Sami\Renderer\TwigExtension')->pathForStaticFile($context, "i/loader.gif"), "html", null, true);
        echo "\" /> loading...
        </div>
        <div class=\"header\">
            <div class=\"nav\">
                <h1>";
        // line 54
        echo twig_escape_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "config", [0 => "title"], "method"), "html", null, true);
        echo "</h1>
                ";
        // line 55
        if ((twig_length_filter($this->env, $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "versions", [])) > 1)) {
            // line 56
            echo "                    <form action=\"#\" method=\"GET\">
                        <select id=\"version-switcher\" name=\"version\">
                            ";
            // line 58
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "versions", []));
            foreach ($context['_seq'] as $context["_key"] => $context["version"]) {
                // line 59
                echo "                                <option value=\"../";
                echo twig_escape_filter($this->env, $context["version"], "html", null, true);
                echo "/index.html\"";
                echo ((($context["version"] == $this->getAttribute(($context["project"] ?? $this->getContext($context, "project")), "version", []))) ? (" selected") : (""));
                echo ">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["version"], "longname", []), "html", null, true);
                echo "</option>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['version'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 61
            echo "                        </select>
                    </form>
                ";
        }
        // line 64
        echo "                <div style=\"clear: both\"></div>
                <table>
                    <tr><td><input type=\"Search\" placeholder=\"Search\" autosave=\"searchdoc\" results=\"10\" id=\"search\" autocomplete=\"off\"></td></tr>
                </table>
            </div>
        </div>
        <div class=\"tree\">
            <ul>
            </ul>
        </div>
        <div class=\"result\">
            <ul>
            </ul>
        </div>
    </div>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "panel.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 64,  133 => 61,  120 => 59,  116 => 58,  112 => 56,  110 => 55,  106 => 54,  99 => 50,  72 => 26,  52 => 9,  48 => 8,  43 => 6,  39 => 5,  35 => 4,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\" \"http://www.w3.org/TR/html4/frameset.dtd\">
<html lang=\"en\">
<head>
    <title>{{ project.config('title') }}</title>
    <link rel=\"stylesheet\" href=\"{{ path('css/reset.css') }}\" type=\"text/css\" media=\"screen\" charset=\"utf-8\">
    <link rel=\"stylesheet\" href=\"{{ path('css/panel.css') }}\" type=\"text/css\" media=\"screen\" charset=\"utf-8\">
    <script src=\"tree.js\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"{{ path('js/jquery-1.3.2.min.js') }}\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script src=\"{{ path('js/searchdoc.js') }}\" type=\"text/javascript\" charset=\"utf-8\"></script>
    <script type=\"text/javascript\" charset=\"utf-8\">
        //<![CDATA[
        \$(document).ready(function(){
            \$('#version-switcher').change(function() {
                window.parent.location = \$(this).val()
            })
        })
       \$(function() {
           \$.ajax({
             url: 'search_index.js',
             dataType: 'script',
             success: function () {
                 \$('.loader').css('display', 'none');
                 var panel = new Searchdoc.Panel(\$('#panel'), search_data, tree, parent.frames[1]);
                 \$('#search').focus();

                 for (var i=0; i < {{ project.config('default_opened_level', 0) }}; i++) {
                     \$('.level_' + i).each(function (\$li) {
                         panel.tree.toggle(\$(this));
                     });
                 }

                 var s = window.parent.location.search.match(/\\?q=([^&]+)/);
                 if (s) {
                     s = decodeURIComponent(s[1]).replace(/\\+/g, ' ');
                     if (s.length > 0)
                     {
                         \$('#search').val(s);
                         panel.search(s, true);
                     }
                 }
             }
           });
       })
        //]]>
    </script>
</head>
<body>
    <div class=\"panel panel_tree\" id=\"panel\">
        <div class=\"loader\">
            <img src=\"{{ path('i/loader.gif') }}\" /> loading...
        </div>
        <div class=\"header\">
            <div class=\"nav\">
                <h1>{{ project.config('title') }}</h1>
                {% if project.versions|length > 1 %}
                    <form action=\"#\" method=\"GET\">
                        <select id=\"version-switcher\" name=\"version\">
                            {% for version in project.versions %}
                                <option value=\"../{{ version }}/index.html\"{{ version == project.version ? ' selected' : '' }}>{{ version.longname }}</option>
                            {% endfor %}
                        </select>
                    </form>
                {% endif %}
                <div style=\"clear: both\"></div>
                <table>
                    <tr><td><input type=\"Search\" placeholder=\"Search\" autosave=\"searchdoc\" results=\"10\" id=\"search\" autocomplete=\"off\"></td></tr>
                </table>
            </div>
        </div>
        <div class=\"tree\">
            <ul>
            </ul>
        </div>
        <div class=\"result\">
            <ul>
            </ul>
        </div>
    </div>
</body>
</html>
", "panel.twig", "/projects/Sami-1.4.1/Sami/Resources/themes/enhanced/panel.twig");
    }
}
