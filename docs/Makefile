DOC=shots

all: $(DOC).pdf
	echo "Done."

$(DOC).html: $(DOC).tex
	htlatex $(DOC).tex

$(DOC).pdf: $(DOC).tex
	pdflatex $(DOC).tex

clean:
	rm -f *.aux
	rm -f *.dvi
	rm -f *.log
	rm -f *.4ct
	rm -f *.4tc
	rm -f *.idv
	rm -f *.lg
	rm -f *.tmp
	rm -f *.out
	rm -f *.xref
	rm -f *~
	rm -f *.ind
	rm -f *.idx
	rm -f *.ilg
	rm -f *.toc

clobber: clean
	rm -f *.pdf
