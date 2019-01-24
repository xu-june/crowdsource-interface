# web server and application for annotation

from flask import Flask, render_template, redirect, url_for
import requests
import json
import os

app = Flask(__name__)

port = 80

@app.route('/annotate')
def annotate():
  img = "00200.jpg"
  return render_template("index.html", image=img)


@app.route('/hello')
@app.route('/hello/<name>')
def hello(name=None):
  return render_template("index.html", name=name)


if __name__ == '__main__':
  app.run(debug=True, host='0.0.0.0', port=port)