# Useful Rules, Axioms, and Mappings for BOTK #

On this directory, we are collecting optional rules and axioms and linksets for BOTK that are 
not part of the formal ontology specification but can be helpful in many cases.

They can be imported in your model when they don't hurt. Some have side-effects that are unwanted in some scenarios.

Such rules are not part of the core BOTK ontology for two reasons:
For semantic aligments with other vocabularies ("ontology mappings"), the problem is that the candidate vocabularies 
are often defined in different ontology languages or language fragments, e.g. RDF-S, OWL DL, and OWL Full. 

Including such alignments in BOTK directly will turn BOTK into OWL Full, which is something we want to avoid as much as we can. 

The reason is that there are applications for BOTK both in the "open, wild Web", where OWL Full will anyway be the likely language
fragment, and incomplete, lightweight reasoning be sufficient, and in controlled, corporate settings, where staying within OWL DL 
is an important feature for consistent DL reasoning.
Also, those alignments that are useful in practice are often broader than correct. 
While they are good in 90% of the cases, they will have unwanted side-effects in 10% of the cases. Thus, including them must be optional 
For additional rules and axioms, the problem is that they raise the bar for computing the full entailment,
which may slow down reasoner performance. Also, they are very promising topics for further research and fine-tuning, 
so the examples given in here will be initial sketches only. Practitioners will be able to improve them a lot in terms of computational 
efficiency and quality. If you are working on business applications that consume BOTK data, this is what you should dedicate resources to.