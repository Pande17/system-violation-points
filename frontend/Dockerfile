FROM node:20-alpine

# Set working directory
WORKDIR /app

# Copy package.json and install dependencies
COPY package*.json ./
RUN npm install

# Copy all application files
COPY . .

# Expose port for HTTP Server
EXPOSE 5173

# Start the application
CMD ["npm", "run", "dev"]
