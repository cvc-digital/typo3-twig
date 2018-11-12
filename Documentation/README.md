# cvc_twig Documentation

The documentation can be build using the `t3docs/render-documentation` Docker image.
The commands must be executed from the root folder of the extension.

```
source <(docker run --rm t3docs/render-documentation show-shell-commands)
dockrun_t3rdf makehtml
```
