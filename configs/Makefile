.PHONY: all

.PHONY: all clean
SITEPREFIX=$(notdir $(abspath $(PWD)/..))

routes.xml: routes.xml.in
	sed -e 's#@SITEPREFIX@#$(SITEPREFIX)#g' < routes.xml.in > routes.xml

all: routes.xml

clean:
	rm -f routes.xml
