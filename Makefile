Moon_tpl_DIR=./Templates/
DIR = $(shell ls $(Moon_tpl_DIR))
DATE=$(shell date +%I:%M%p)
HR=\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#\#

all: sayhello buildall saygoodbye

buildall: buildtpls

buildtpls:
	@$(foreach dir,$(DIR),cd ./$(Moon_tpl_DIR)/$(dir); make all; cd ../..;)
	@echo ""

sayhello:
	@echo " ${HR}"
	@echo ""
	@echo " Templates : ${DIR}"
	@echo ""
	@echo "   ------------------------------------"
	@echo "  |  Building moon framework assets... |"
	@echo "   ------------------------------------"
	@echo ""

saygoodbye:
	@echo " » Look at your clock ! It's ${DATE}, tea time !."
	@echo ""
	@echo " » You can now use the moon framework,"
	@echo " » Thanks you !"
	@echo ""
	@echo " © lambdaweb - 2Ø13 - www.lambdaweb.fr"
	@echo ""
	@echo " ${HR}"

.PHONY: buildall