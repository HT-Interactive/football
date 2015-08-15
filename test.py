#!/Python27/python

import nfldb

print "Content-type: text/html"
print 
print "<html>"
print "<head>"
print "<title>NFLDB Test Script</title>"
print "</head>"
print "<body>"
print "<div>"

db = nfldb.connect()

phase, year, week = nfldb.current(db)
q = nfldb.Query(db).game(season_year=year, season_type=phase, week=week)
for g in q.as_games():
    #print '%s (%d) at %s (%d)' % (g.home_team, g.home_score,
    #                              g.away_team, g.away_score)
    print g
print "</div>"
print "</body>"
print "</html>"