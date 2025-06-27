<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/">
    <xsl:apply-templates select="xltechlog"/>
  </xsl:template>

  <xsl:template match="xltechlog">
    <table id="logTable" class="display" style="width:100%">
      <thead>
        <tr>
          <th>Time</th>
          <th>Machine</th>
          <th>User</th>
          <th>Activity</th>
          <th>Event</th>
          <th>Event Description</th>
        </tr>
      </thead>
      <tbody>
        <xsl:for-each select="activity">
          <tr>
<td>
  <xsl:variable name="original" select="time"/>
  <xsl:variable name="day" select="substring($original, 4, 2)"/>
  <xsl:variable name="month" select="substring($original, 1, 2)"/>
  <xsl:variable name="year" select="substring($original, 7, 4)"/>
  <xsl:variable name="hour" select="substring($original, 12, 2)"/>
  <xsl:variable name="minute" select="substring($original, 15, 2)"/>
  <xsl:variable name="second" select="substring($original, 18, 2)"/>
  
  <xsl:attribute name="data-order">
    <xsl:value-of select="concat($year, '-', $month, '-', $day, 'T', $hour, ':', $minute, ':', $second)"/>
  </xsl:attribute>

  <xsl:value-of select="concat($day, '/', $month, '/', $year, ' ', $hour, ':', $minute, ':', $second)"/>
</td>



            <td><xsl:value-of select="mach"/></td>
            <td><xsl:value-of select="user"/></td>
            <td><xsl:value-of select="acID"/></td>
            <td><xsl:value-of select="evID"/></td>
            <td><xsl:value-of select="evdesc"/></td>
          </tr>
        </xsl:for-each>
      </tbody>
    </table>
  </xsl:template>

</xsl:stylesheet>
