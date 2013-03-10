BOOTSTRAP_DIR=./System/Assets/

DATE=$(shell date +%I:%M%p)
HR=\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#

all: sayhello buildall saygoodbye

buildall: buildbs

buildbs: 
	cd $(BOOTSTRAP_DIR); make bootstrap
	@echo ""

sayhello:
	@echo ""
	@echo "${HR}"
	@echo ""
	@echo "Hi people !"
	@echo "   ------------------------------------"
	@echo "  |  Building moon framework...  |"
	@echo "   ------------------------------------"
	@echo ""

saygoodbye:
	@echo "   --------------------------------"
	@echo "‣ Look at your clock ! It's ${DATE}, tea time !."
	@echo ""
	@echo "‣ You can now use the moon framework,"
	@echo "‣ Thanks you !"
	@echo ""
	@echo "(c) lambdaweb - www.lambdaweb.fr"

.PHONY: build