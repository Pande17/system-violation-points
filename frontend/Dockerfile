FROM node:20-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy all application files
COPY . .

RUN chmod -R +x /app/node_modules/.bin

# Expose port for HTTP Server
EXPOSE 5173

# Start the application
CMD ["npm", "run", "dev"]
