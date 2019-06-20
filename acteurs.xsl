<?xml version="1.0"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
  version="1.0" 
  xmlns:mochi="http://mochikit.com/examples/sortable_tables">

  <xsl:output method="html"/>

  <xsl:template match="/categories">

    <html>

      <head>

        <title>
          <xsl:value-of select="@titre"/>
        </title>
        <meta charset="utf-8" />

        <script type="text/javascript" 
          src="MochiKit/MochiKit.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Base.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Iter.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Logging.js"></script>

        <script type="text/javascript" 
          src="MochiKit/DateTime.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Format.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Async.js"></script>

        <script type="text/javascript" 
          src="MochiKit/DOM.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Selector.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Style.js"></script>

        <script type="text/javascript" 
          src="MochiKit/LoggingPane.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Color.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Signal.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Position.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Visual.js"></script>

        <script type="text/javascript" 
          src="MochiKit/DragAndDrop.js"></script>

        <script type="text/javascript" 
          src="MochiKit/Sortable.js"></script>

        <script type="text/javascript" 
          src="scripts/SortableManager.js"></script>

        <style>
@page {
  size: landscape;   /* auto is the initial value */
  scale: 10%;
  margin: 5mm 5mm 5mm 5mm;
}
        </style>
      </head>

      <body>

        <!-- Main -->
        <h1>
          <xsl:value-of select="@titre"/>
        </h1>
        <p>Les comptoirs de change apparaissent surligne's.</p>
        <xsl:for-each select="categorie">

          <h2>
            <xsl:value-of select="@type"/>
          </h2>
          <xsl:for-each select="scat">
            <xsl:choose>
              <xsl:when test="@type=''">
              </xsl:when>
              <xsl:otherwise>
                <h2>
                  <xsl:value-of select="@type"/>
                </h2>
                <div id="acteurs">
                  <table id="table_des_Acteurs" 
                    class="sortable" 
                    border="1" 
                    cellspacing="0" 
                    cellpadding="4" 
                    summary="Liste des Acteurs">

                    <thead>
                      <tr>
                        <th mochi:format="str">Nom</th>
                        <th mochi:format="str">Adresse</th>
                        <th mochi:format="str">Telephone</th>
                        <th mochi:format="str">Site Internet</th>
                        <th mochi:format="str">Bref</th>
                      </tr>

                    </thead>
                    <tbody>
                      <xsl:for-each select="acteur">
                        <xsl:variable name="altColor">
                          <xsl:choose>
                            <xsl:when test="@comptoir='oui'">#FFFF00</xsl:when>
                            <xsl:otherwise>#FFFFFF</xsl:otherwise>
                          </xsl:choose>
                        </xsl:variable>
                        <tr bgcolor="{$altColor}">
                          <xsl:choose>
                            <xsl:when test="@attente='true'">
                            </xsl:when>
                            <xsl:otherwise>
                              <td>
                                <xsl:value-of select="@titre"/>
                              </td>

                              <td>
                                <xsl:value-of select="@adresse"/>
                              </td>

                              <td>
                                <xsl:value-of select="@telephone"/>
                              </td>

                              <td>
                                <a>
                                  <xsl:attribute name="href">
                                    <xsl:value-of select="@siteweb"/>
                                  </xsl:attribute>
                                  <xsl:value-of select="@siteweb"/>
                                </a>
                              </td>
                              <td>
                                <xsl:value-of select="@bref"/>
                              </td>

                            </xsl:otherwise>
                          </xsl:choose>
                        </tr>

                      </xsl:for-each>

                    </tbody>
                  </table>

                </div>


              </xsl:otherwise>
            </xsl:choose>
          </xsl:for-each>
        </xsl:for-each>

        <script type="text/javascript">

		    var summaryManager = new SortableManager();

		    addLoadEvent(function () {

			summaryManager.initWithTable('table_des_Acteurs');

		    });

        </script>

      </body>

    </html>

  </xsl:template>



</xsl:stylesheet>

